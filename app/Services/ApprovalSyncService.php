<?php

namespace App\Services;

use App\Models\PanelAssignment;
use App\Models\PanelAssignmentReview;
use App\Models\ThesisDocument;

class ApprovalSyncService
{
    /**
     * Ensure all panel members have review records
     */
    public function ensureReviewRecordsExist(PanelAssignment $panelAssignment): void
    {
        $reviewers = $this->getAllReviewers($panelAssignment);
        
        foreach ($reviewers as $reviewerId => $role) {
            PanelAssignmentReview::firstOrCreate([
                'panel_assignment_id' => $panelAssignment->id,
                'thesis_document_id' => $panelAssignment->thesis_document_id,
                'reviewer_id' => $reviewerId,
            ], [
                'reviewer_role' => $role,
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Sync existing thesis document approvals to panel assignment reviews
     */
    public function syncExistingApprovals(PanelAssignment $panelAssignment): void
    {
        $thesisDocument = $panelAssignment->thesisDocument;
        
        if (!$thesisDocument || !$thesisDocument->reviewed_by) {
            return;
        }

        $reviewerId = $thesisDocument->reviewed_by;
        $role = $this->determineReviewerRole($panelAssignment, $reviewerId);
        
        if ($role) {
            $reviewStatus = $this->mapThesisStatusToReviewStatus($thesisDocument->status);
            
            PanelAssignmentReview::updateOrCreate([
                'panel_assignment_id' => $panelAssignment->id,
                'reviewer_id' => $reviewerId,
            ], [
                'thesis_document_id' => $thesisDocument->id,
                'reviewer_role' => $role,
                'status' => $reviewStatus,
                'review_comments' => $thesisDocument->review_comments,
                'reviewed_at' => $thesisDocument->reviewed_at,
            ]);
        }
    }

    /**
     * Sync final manuscript approvals to panel assignments
     */
    public function syncFinalManuscriptApprovals(int $studentId): void
    {
        // Find final manuscript for this student
        $finalManuscript = ThesisDocument::where('user_id', $studentId)
            ->where('document_type', 'final_manuscript')
            ->where('status', 'approved')
            ->first();
        
        if (!$finalManuscript) {
            return;
        }

        // Find panel assignments for this student
        $panelAssignments = PanelAssignment::where('student_id', $studentId)
            ->where('defense_type', 'final_defense')
            ->get();

        foreach ($panelAssignments as $panelAssignment) {
            $reviewerId = $finalManuscript->reviewed_by;
            $role = $this->determineReviewerRole($panelAssignment, $reviewerId);
            
            if ($role) {
                $reviewStatus = $this->mapThesisStatusToReviewStatus($finalManuscript->status);
                
                // Update existing review or create new one
                $existingReview = PanelAssignmentReview::where('panel_assignment_id', $panelAssignment->id)
                    ->where('reviewer_id', $reviewerId)
                    ->first();
                
                if ($existingReview) {
                    // Update existing review
                    $existingReview->update([
                        'thesis_document_id' => $finalManuscript->id,
                        'reviewer_role' => $role,
                        'status' => $reviewStatus,
                        'review_comments' => $finalManuscript->review_comments,
                        'reviewed_at' => $finalManuscript->reviewed_at,
                    ]);
                } else {
                    // Create new review using updateOrCreate to avoid constraint violations
                    PanelAssignmentReview::updateOrCreate([
                        'panel_assignment_id' => $panelAssignment->id,
                        'reviewer_id' => $reviewerId,
                    ], [
                        'thesis_document_id' => $finalManuscript->id,
                        'reviewer_role' => $role,
                        'status' => $reviewStatus,
                        'review_comments' => $finalManuscript->review_comments,
                        'reviewed_at' => $finalManuscript->reviewed_at,
                    ]);
                }
            }
        }
    }

    /**
     * Get all reviewers for a panel assignment
     */
    private function getAllReviewers(PanelAssignment $panelAssignment): array
    {
        $reviewers = [];
        
        // Add panel chair
        if ($panelAssignment->panel_chair_id) {
            $reviewers[$panelAssignment->panel_chair_id] = 'panel_chair';
        }
        
        // Add secretary
        if ($panelAssignment->secretary_id) {
            $reviewers[$panelAssignment->secretary_id] = 'panel_member';
        }
        
        // Add panel members
        if (!empty($panelAssignment->panel_member_ids)) {
            foreach ($panelAssignment->panel_member_ids as $memberId) {
                $reviewers[$memberId] = 'panel_member';
            }
        }
        
        // Add adviser
        if ($panelAssignment->thesisDocument?->adviser_id) {
            $reviewers[$panelAssignment->thesisDocument->adviser_id] = 'adviser';
        }
        
        return $reviewers;
    }

    /**
     * Determine reviewer role in panel assignment
     */
    private function determineReviewerRole(PanelAssignment $panelAssignment, int $reviewerId): ?string
    {
        if ($panelAssignment->panel_chair_id == $reviewerId) {
            return 'panel_chair';
        } elseif ($panelAssignment->secretary_id == $reviewerId) {
            return 'panel_member';
        } elseif (in_array($reviewerId, $panelAssignment->panel_member_ids ?? [])) {
            return 'panel_member';
        } elseif ($panelAssignment->thesisDocument?->adviser_id == $reviewerId) {
            return 'adviser';
        }
        
        return null;
    }

    /**
     * Map thesis document status to review status
     */
    private function mapThesisStatusToReviewStatus(string $status): string
    {
        return match($status) {
            'approved' => 'approved',
            'returned_for_revision' => 'needs_revision',
            'under_review' => 'pending',
            default => 'pending'
        };
    }
}
