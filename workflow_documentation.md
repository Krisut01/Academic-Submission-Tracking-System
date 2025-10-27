 COMPLETE THESIS WORKFLOW
PHASE 1: PROPOSAL STAGE
Student submits Proposal Form (document_type: 'proposal')
Faculty reviews proposal → Approved/Rejected
Student submits Approval Sheet (document_type: 'approval_sheet')
Faculty reviews approval sheet → Approved/Rejected
Student submits Panel Assignment Request (document_type: 'panel_assignment')
Admin assigns panel → Creates PanelAssignment with defense_type: 'proposal_defense'
Admin schedules proposal defense
Student attends proposal defense
Student marks defense as completed → Status: completed
Student submits approval sheet for proposal defense
PHASE 2: FINAL DEFENSE STAGE
Student submits Final Manuscript (document_type: 'final_manuscript')
Faculty reviews final manuscript → Approved/Rejected
Student submits new Approval Sheet for final defense
Faculty reviews final approval sheet → Approved/Rejected
Student submits new Panel Assignment Request for final defense
Admin assigns new panel → Creates new PanelAssignment with defense_type: 'final_defense'
Admin schedules final defense
Student attends final defense
Student marks final defense as completed → Status: completed
Student submits final approval sheet
PHASE 3: COMPLETION
Thesis process completed ✅
🔄 KEY ITERATION POINTS:
After Proposal Defense:
Student can submit Final Manuscript
New Panel Assignment Request for final defense
New Panel Assignment with defense_type: 'final_defense'
After Final Defense:
Process is complete
No more iterations needed
Re-defense Scenarios:
If defense fails → defense_type: 'redefense'
Student can request re-defense with same workflow