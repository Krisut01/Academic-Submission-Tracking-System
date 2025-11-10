## Figure 3.2.3 Context Diagram

```mermaid
flowchart LR
    subgraph ExternalActors[External Actors]
        STUD[Students]
        FAC[Faculty & Panel Members]
        ADM[Program Admin / Chair]
        REG[Registrar & Academic Office]
        MAIL[Email/SMS Providers]
    end

    SYS[Thesis Management System\n(Laravel Application)]

    STUD -->|Submit proposals,\nmanuscripts, forms| SYS
    SYS -->|Status updates,\nfeedback| STUD

    FAC -->|Review requests,\nsubmit evaluations| SYS
    SYS -->|Defense schedules,\nnotifications| FAC

    ADM -->|Approve panel assignments,\nmanage defenses| SYS
    SYS -->|Dashboards,\naction alerts| ADM

    REG -->|Academic requirements,\nclearance criteria| SYS
    SYS -->|Final clearance data,\nreports| REG

    SYS -->|Email/SMS notifications| MAIL
    MAIL -->|Delivery status| SYS
```
