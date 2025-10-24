graph TD
    %% Actors
    Student[👨‍🎓 Student]
    Admin[👨‍💼 Admin]
    Faculty[👨‍🏫 Faculty]
    
    %% Phase 1: Proposal Form
    Student -->|1. Submit| ProposalForm[📝 Proposal Form]
    ProposalForm -->|2. Under Review| FacultyReviewProposal[👨‍🏫 Faculty Reviews Proposal Form]
    FacultyReviewProposal -->|3a. Rejected| ProposalFormRejected[❌ Proposal Form Rejected]
    FacultyReviewProposal -->|3b. Approved| ProposalFormApproved[✅ Proposal Form Approved]
    
    %% Phase 2: Approval Sheet
    ProposalFormApproved -->|4. Submit| ApprovalSheet[📋 Approval Sheet]
    ApprovalSheet -->|5. Under Review| FacultyReviewApproval[👨‍🏫 Faculty Reviews Approval Sheet]
    FacultyReviewApproval -->|6a. Rejected| ApprovalSheetRejected[❌ Approval Sheet Rejected]
    FacultyReviewApproval -->|6b. Approved| ApprovalSheetApproved[✅ Approval Sheet Approved]
    
    %% Phase 3: Panel Assignment Request
    ApprovalSheetApproved -->|7. Submit| PanelAssignmentRequest[👥 Panel Assignment Request]
    PanelAssignmentRequest -->|8. Admin Review| AdminReviewPanel[👨‍💼 Admin Reviews Panel Assignment]
    AdminReviewPanel -->|9a. Rejected| PanelAssignmentRejected[❌ Panel Assignment Rejected]
    AdminReviewPanel -->|9b. Approved| PanelAssignmentApproved[✅ Panel Assignment Approved]
    
    %% Phase 4: Schedule Proposal Defense
    PanelAssignmentApproved -->|10. Schedule| ScheduleProposalDefense[📅 Schedule Proposal Defense]
    ScheduleProposalDefense -->|11. Defense Scheduled| ProposalDefenseScheduled[🎯 Proposal Defense Scheduled]
    ProposalDefenseScheduled -->|12. Student Attends| StudentAttendsProposal[👨‍🎓 Student Attends Proposal Defense]
    StudentAttendsProposal -->|13. Admin Evaluation| AdminEvaluatesProposal[👨‍💼 Admin Evaluates Proposal Defense]
    AdminEvaluatesProposal -->|14. Mark Completed| ProposalDefenseCompleted[✅ Proposal Defense Completed]
    
    %% Phase 5: Final Manuscript
    ProposalDefenseCompleted -->|15. Submit| FinalManuscript[📄 Final Manuscript]
    FinalManuscript -->|16. Faculty Review| FacultyReviewFinal[👨‍🏫 Faculty Reviews Final Manuscript]
    FacultyReviewFinal -->|17a. Rejected| FinalManuscriptRejected[❌ Final Manuscript Rejected]
    FacultyReviewFinal -->|17b. Approved| FinalManuscriptApproved[✅ Final Manuscript Approved]
    
    %% Phase 6: Schedule Final Defense
    FinalManuscriptApproved -->|18. Schedule| ScheduleFinalDefense[📅 Schedule Final Defense]
    ScheduleFinalDefense -->|19. Final Defense Scheduled| FinalDefenseScheduled[🎯 Final Defense Scheduled]
    FinalDefenseScheduled -->|20. Student Attends| StudentAttendsFinal[👨‍🎓 Student Attends Final Defense]
    StudentAttendsFinal -->|21. Admin Evaluation| AdminEvaluatesFinal[👨‍💼 Admin Evaluates Final Defense]
    AdminEvaluatesFinal -->|22. Mark Completed| FinalDefenseCompleted[✅ Final Defense Completed]
    
    %% Notifications
    ProposalDefenseScheduled -.->|Notify| Student
    ProposalDefenseCompleted -.->|Notify| Student
    FinalDefenseScheduled -.->|Notify| Student
    FinalDefenseCompleted -.->|Notify| Student
    
    %% Panel Member Notifications
    ProposalDefenseScheduled -.->|Notify| Faculty
    ProposalDefenseCompleted -.->|Notify| Faculty
    FinalDefenseScheduled -.->|Notify| Faculty
    FinalDefenseCompleted -.->|Notify| Faculty
    
    %% Styling
    classDef studentAction fill:#e1f5fe,stroke:#01579b,stroke-width:2px
    classDef adminAction fill:#f3e5f5,stroke:#4a148c,stroke-width:2px
    classDef facultyAction fill:#e8f5e8,stroke:#1b5e20,stroke-width:2px
    classDef completed fill:#c8e6c9,stroke:#2e7d32,stroke-width:3px
    classDef rejected fill:#ffcdd2,stroke:#c62828,stroke-width:2px
    classDef document fill:#fff3e0,stroke:#ef6c00,stroke-width:2px
    
    class ProposalForm,ApprovalSheet,PanelAssignmentRequest,FinalManuscript,StudentAttendsProposal,StudentAttendsFinal studentAction
    class AdminReviewPanel,ScheduleProposalDefense,AdminEvaluatesProposal,ScheduleFinalDefense,AdminEvaluatesFinal adminAction
    class FacultyReviewProposal,FacultyReviewApproval,FacultyReviewFinal facultyAction
    class ProposalDefenseCompleted,FinalDefenseCompleted completed
    class ProposalFormRejected,ApprovalSheetRejected,PanelAssignmentRejected,FinalManuscriptRejected rejected
    class ProposalForm,ApprovalSheet,PanelAssignmentRequest,FinalManuscript document