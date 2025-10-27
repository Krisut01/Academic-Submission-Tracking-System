# Thesis Management System - Data Flow Diagram

## High-Level Data Flow Overview

```mermaid
graph TD
    A[Student] --> B[Submit Proposal Document]
    B --> C{Adviser Review}
    C -->|Approved| D[Submit Panel Assignment Request]
    C -->|Rejected/Revision| B
    D --> E{Admin Reviews Panel Assignment Request}
    E -->|Approved| F[Admin Assigns Panel & Schedules Defense]
    E -->|Rejected/Revision| D
    F --> G[Student Attends Defense]
    G --> H[Student Marks Defense Complete]
    H --> I[Submit Approval Sheet with Defense Results & Grades]
    I --> J{Faculty Review Approval Sheet}
    J -->|Approved| K[Submit Final Manuscript]
    J -->|Rejected/Revision| I
    K --> L{Faculty Review Final Manuscript}
    L -->|Approved| M[Submit Final Panel Assignment Request]
    L -->|Rejected/Revision| K
    M --> N{Admin Reviews Final Panel Assignment Request}
    N -->|Approved| O[Admin Assigns Final Panel & Schedules Defense]
    N -->|Rejected/Revision| M
    O --> P[Student Attends Final Defense]
    P --> Q[Student Marks Final Defense Complete]
    Q --> R[Submit Final Approval Sheet with Defense Results & Grades]
    R --> S{Faculty Review Final Approval Sheet}
    S -->|Approved| T[Thesis Process Complete ✅]
    S -->|Rejected/Revision| R

    %% Re-defense scenarios
    G -->|Failed| U[Request Re-defense]
    U --> V[Admin Assigns Re-defense Panel]
    V --> W[Admin Schedules Re-defense]
    W --> X[Student Attends Re-defense]
    X --> Y{Re-defense Result}
    Y -->|Pass| I
    Y -->|Fail| U

    P -->|Failed| Z[Request Final Re-defense]
    Z --> AA[Admin Assigns Final Re-defense Panel]
    AA --> BB[Admin Schedules Final Re-defense]
    BB --> CC[Student Attends Final Re-defense]
    CC --> DD{Final Re-defense Result}
    DD -->|Pass| R
    DD -->|Fail| Z
```

## Detailed Entity Relationship Flow

```mermaid
erDiagram
    USER ||--o{ THESIS_DOCUMENT : submits
    USER ||--o{ PANEL_ASSIGNMENT : assigned_to
    USER ||--o{ PANEL_ASSIGNMENT_REVIEW : reviews
    THESIS_DOCUMENT ||--o{ PANEL_ASSIGNMENT : linked_to
    PANEL_ASSIGNMENT ||--o{ PANEL_ASSIGNMENT_REVIEW : has_reviews
    PANEL_ASSIGNMENT ||--o{ PANEL_EVALUATION : evaluated_by

    USER {
        int id PK
        string name
        string email
        string role
        datetime created_at
    }

    THESIS_DOCUMENT {
        int id PK
        int user_id FK
        string document_type
        string title
        string status
        int adviser_id FK
        datetime submission_date
        datetime reviewed_at
        int reviewed_by FK
        string review_comments
    }

    PANEL_ASSIGNMENT {
        int id PK
        int student_id FK
        int thesis_document_id FK
        string defense_type
        string status
        datetime defense_date
        string defense_venue
        json panel_members
        int panel_chair_id FK
        int secretary_id FK
        datetime completed_at
    }

    PANEL_ASSIGNMENT_REVIEW {
        int id PK
        int panel_assignment_id FK
        int reviewer_id FK
        string reviewer_role
        string status
        text review_comments
        datetime reviewed_at
    }
```

## Document Types and Status Flow

```mermaid
stateDiagram-v2
    [*] --> Pending: Student submits document
    
    Pending --> UnderReview: Faculty starts review
    UnderReview --> Approved: Faculty approves
    UnderReview --> ReturnedForRevision: Faculty requests revision
    UnderReview --> Rejected: Faculty rejects
    
    ReturnedForRevision --> Pending: Student resubmits
    Rejected --> [*]: Process ends
    
    Approved --> [*]: Document approved
    
    note right of UnderReview
        Faculty can:
        - Approve document
        - Request revisions
        - Reject document
    end note
    
    note right of ReturnedForRevision
        Student must:
        - Address feedback
        - Resubmit document
        - Version number increments
    end note
```

## Defense Types and Panel Assignment Flow

```mermaid
graph LR
    A[Panel Assignment Request] --> B{Defense Type}
    B -->|proposal_defense| C[Proposal Defense Panel]
    B -->|final_defense| D[Final Defense Panel]
    B -->|redefense| E[Re-defense Panel]
    
    C --> F[Admin Assigns Panel Members]
    D --> F
    E --> F
    
    F --> G[Admin Schedules Defense]
    G --> H[Student Attends Defense]
    H --> I[Student Marks Complete]
    I --> J{Defense Result}
    J -->|Pass| K[Next Phase]
    J -->|Fail| L[Request Re-defense]
    L --> E
```

## Key Data Entities and Their Relationships

### 1. **User Entity**
- **Students**: Submit documents, attend defenses
- **Faculty**: Review documents, serve on panels
- **Admins**: Assign panels, schedule defenses

### 2. **ThesisDocument Entity**
- **Document Types**: proposal, approval_sheet, panel_assignment, final_manuscript
- **Status Flow**: pending → under_review → approved/rejected/returned_for_revision
- **Review Process**: Adviser reviews, faculty reviews, panel reviews

### 3. **PanelAssignment Entity**
- **Defense Types**: proposal_defense, final_defense, redefense
- **Panel Structure**: Chair, Secretary, Panel Members
- **Status Tracking**: scheduled, completed, postponed, cancelled

### 4. **PanelAssignmentReview Entity**
- **Reviewer Roles**: adviser, panel_chair, panel_member
- **Review Status**: pending, approved, rejected, needs_revision
- **Review Process**: Individual reviews by each panel member

## Workflow Phases Summary

### **Phase 1: Proposal Stage**
1. Student submits Proposal Form
2. Adviser reviews → Approved/Rejected
3. Student submits Panel Assignment Request
4. **Admin reviews Panel Assignment Request** → Approved/Rejected
5. **Admin assigns panel and schedules defense** (defense_type: 'proposal_defense')
6. Student attends and completes defense
7. Student marks defense as complete
8. **Student submits Approval Sheet** (with defense results, grades, and panel recommendations)
9. **Faculty reviews approval sheet** → Approved/Rejected

### **Phase 2: Final Defense Stage**
1. Student submits Final Manuscript
2. Faculty reviews → Approved/Rejected
3. Student submits Final Panel Assignment Request
4. **Admin reviews Final Panel Assignment Request** → Approved/Rejected
5. **Admin assigns final panel and schedules defense** (defense_type: 'final_defense')
6. Student attends and completes final defense
7. Student marks final defense as complete
8. **Student submits Final Approval Sheet** (with defense results, grades, and panel recommendations)
9. **Faculty reviews final approval sheet** → Approved/Rejected

### **Phase 3: Completion**
- Thesis process completed ✅
- All documents approved
- All defenses passed
- All approval sheets with grades submitted and approved

## Key Workflow Points:
- **Panel Assignment Request** must be approved by Admin before defense can be scheduled
- **Defense must be completed** before Approval Sheet can be submitted
- **Approval Sheet contains the defense results and grades** from the completed defense
- **Each step requires approval** before proceeding to the next step

## Key Insight: Approval Sheet Timing
The **Approval Sheet is submitted AFTER the defense** and contains:
- Defense date, time, and venue (already completed)
- Final grade/rating from the panel
- Committee recommendations and feedback
- Panel member signatures and approval status
- Any conditional requirements or revision notes

## Use Case Diagram - Actor Interactions

```mermaid
graph TB
    subgraph "Actors"
        S[Student]
        A[Adviser]
        F[Faculty]
        AD[Admin]
        P[Panel Members]
    end
    
    subgraph "Student Use Cases"
        S --> UC1[Submit Proposal Document]
        S --> UC2[Submit Approval Sheet]
        S --> UC3[Submit Panel Assignment Request]
        S --> UC4[Submit Final Manuscript]
        S --> UC5[Attend Defense]
        S --> UC6[Mark Defense Complete]
        S --> UC7[View Document Status]
        S --> UC8[Edit Returned Documents]
    end
    
    subgraph "Adviser Use Cases"
        A --> UC9[Review Proposal]
        A --> UC10[Approve/Reject Documents]
        A --> UC11[Request Revisions]
        A --> UC12[View Student Progress]
    end
    
    subgraph "Faculty Use Cases"
        F --> UC13[Review Documents]
        F --> UC14[Approve/Reject Documents]
        F --> UC15[Provide Feedback]
        F --> UC16[View Assigned Documents]
    end
    
    subgraph "Admin Use Cases"
        AD --> UC17[Assign Panel Members]
        AD --> UC18[Schedule Defenses]
        AD --> UC19[Manage Panel Assignments]
        AD --> UC20[View System Reports]
        AD --> UC21[Assign Defense Venues]
    end
    
    subgraph "Panel Member Use Cases"
        P --> UC22[Review Assigned Documents]
        P --> UC23[Submit Panel Reviews]
        P --> UC24[Attend Defense Sessions]
        P --> UC25[Evaluate Student Performance]
    end
    
    %% Relationships
    UC1 -.-> UC9
    UC2 -.-> UC13
    UC3 -.-> UC17
    UC4 -.-> UC13
    UC5 -.-> UC24
    UC6 -.-> UC25
    UC9 -.-> UC10
    UC13 -.-> UC14
    UC17 -.-> UC18
    UC22 -.-> UC23
```

## System Data Flow - Detailed Process

```mermaid
sequenceDiagram
    participant S as Student
    participant TD as ThesisDocument
    participant A as Adviser
    participant F as Faculty
    participant PA as PanelAssignment
    participant AD as Admin
    participant P as Panel Members
    
    Note over S,P: Phase 1: Proposal Stage
    
    S->>TD: Submit Proposal Document
    TD->>A: Notify Adviser
    A->>TD: Review & Approve/Reject
    alt Document Approved
        S->>PA: Submit Panel Assignment Request
        PA->>AD: Notify Admin
        AD->>PA: Review Panel Assignment Request
        alt Panel Request Approved
            AD->>PA: Assign Panel Members & Schedule Defense
            PA->>P: Notify Panel Members
            S->>PA: Attend Defense
            S->>PA: Mark Defense Complete
            S->>TD: Submit Approval Sheet (with defense results & grade)
            TD->>F: Notify Faculty
            F->>TD: Review & Approve/Reject
            alt Approval Sheet Approved
                Note over S,P: Ready for Final Manuscript
            else Approval Sheet Rejected
                F->>TD: Request Revisions
                S->>TD: Resubmit Approval Sheet
            end
        else Panel Request Rejected
            AD->>PA: Request Revisions
            S->>PA: Resubmit Panel Assignment Request
        end
    else Document Rejected
        A->>TD: Request Revisions
        S->>TD: Resubmit Document
    end
    
    Note over S,P: Phase 2: Final Defense Stage
    
    S->>TD: Submit Final Manuscript
    TD->>F: Notify Faculty
    F->>TD: Review & Approve/Reject
    alt Final Manuscript Approved
        S->>PA: Submit Final Panel Assignment Request
        PA->>AD: Notify Admin
        AD->>PA: Review Final Panel Assignment Request
        alt Final Panel Request Approved
            AD->>PA: Assign Final Panel & Schedule Defense
            PA->>P: Notify Panel Members
            S->>PA: Attend Final Defense
            S->>PA: Mark Final Defense Complete
            S->>TD: Submit Final Approval Sheet (with defense results & grade)
            TD->>F: Notify Faculty
            F->>TD: Review & Approve/Reject
            alt Final Approval Sheet Approved
                Note over S,P: Thesis Process Complete ✅
            else Final Approval Sheet Rejected
                F->>TD: Request Revisions
                S->>TD: Resubmit Final Approval Sheet
            end
        else Final Panel Request Rejected
            AD->>PA: Request Revisions
            S->>PA: Resubmit Final Panel Assignment Request
        end
    end
```

## Re-defense Scenarios
- **Proposal Re-defense**: If proposal defense fails
- **Final Re-defense**: If final defense fails
- **Re-defense Type**: defense_type: 'redefense'
- **Same Workflow**: Follows same panel assignment and defense process
