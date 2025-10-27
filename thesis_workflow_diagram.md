# Thesis Management System - Data Flow Diagram

## High-Level Data Flow Overview

```mermaid
graph TD
    A[Student] --> B[Submit Proposal Document]
    B --> C{Adviser Review}
    C -->|Approved| D[Submit Approval Sheet]
    C -->|Rejected/Revision| B
    D --> E{Faculty Review}
    E -->|Approved| F[Submit Panel Assignment Request]
    E -->|Rejected/Revision| D
    F --> G[Admin Assigns Panel]
    G --> H[Admin Schedules Defense]
    H --> I[Student Attends Defense]
    I --> J[Student Marks Defense Complete]
    J --> K[Submit Final Manuscript]
    K --> L{Faculty Review}
    L -->|Approved| M[Submit Final Approval Sheet]
    L -->|Rejected/Revision| K
    M --> N{Faculty Review}
    N -->|Approved| O[Submit Final Panel Assignment]
    N -->|Rejected/Revision| M
    O --> P[Admin Assigns Final Panel]
    P --> Q[Admin Schedules Final Defense]
    Q --> R[Student Attends Final Defense]
    R --> S[Student Marks Final Defense Complete]
    S --> T[Thesis Process Complete ✅]

    %% Re-defense scenarios
    I -->|Failed| U[Request Re-defense]
    U --> V[Admin Assigns Re-defense Panel]
    V --> W[Admin Schedules Re-defense]
    W --> X[Student Attends Re-defense]
    X --> Y{Re-defense Result}
    Y -->|Pass| K
    Y -->|Fail| U

    R -->|Failed| Z[Request Final Re-defense]
    Z --> AA[Admin Assigns Final Re-defense Panel]
    AA --> BB[Admin Schedules Final Re-defense]
    BB --> CC[Student Attends Final Re-defense]
    CC --> DD{Final Re-defense Result}
    DD -->|Pass| T
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
3. Student submits Approval Sheet
4. Faculty reviews → Approved/Rejected
5. Student submits Panel Assignment Request
6. Admin assigns panel (defense_type: 'proposal_defense')
7. Admin schedules proposal defense
8. Student attends and completes defense

### **Phase 2: Final Defense Stage**
1. Student submits Final Manuscript
2. Faculty reviews → Approved/Rejected
3. Student submits Final Approval Sheet
4. Faculty reviews → Approved/Rejected
5. Student submits Final Panel Assignment Request
6. Admin assigns final panel (defense_type: 'final_defense')
7. Admin schedules final defense
8. Student attends and completes final defense

### **Phase 3: Completion**
- Thesis process completed ✅
- All documents approved
- All defenses passed

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
        S->>TD: Submit Approval Sheet
        TD->>F: Notify Faculty
        F->>TD: Review & Approve/Reject
        alt Approval Sheet Approved
            S->>PA: Submit Panel Assignment Request
            PA->>AD: Notify Admin
            AD->>PA: Assign Panel Members
            AD->>PA: Schedule Defense
            PA->>P: Notify Panel Members
            S->>PA: Attend Defense
            S->>PA: Mark Defense Complete
        else Approval Sheet Rejected
            F->>TD: Request Revisions
            S->>TD: Resubmit Document
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
        S->>TD: Submit Final Approval Sheet
        TD->>F: Notify Faculty
        F->>TD: Review & Approve/Reject
        alt Final Approval Sheet Approved
            S->>PA: Submit Final Panel Assignment Request
            PA->>AD: Notify Admin
            AD->>PA: Assign Final Panel
            AD->>PA: Schedule Final Defense
            PA->>P: Notify Panel Members
            S->>PA: Attend Final Defense
            S->>PA: Mark Final Defense Complete
            Note over S,P: Thesis Process Complete ✅
        end
    end
```

## Re-defense Scenarios
- **Proposal Re-defense**: If proposal defense fails
- **Final Re-defense**: If final defense fails
- **Re-defense Type**: defense_type: 'redefense'
- **Same Workflow**: Follows same panel assignment and defense process
