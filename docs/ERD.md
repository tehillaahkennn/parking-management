

\# Entity Relationship Diagram (ERD)



\## Parking Management System



```mermaid

erDiagram

&#x20;   USERS o|--o{ AUDIT\_LOGS : generates

&#x20;   VEHICLES ||--o{ PARKING\_RECORDS : has

&#x20;   PARKING\_SLOTS ||--o{ PARKING\_RECORDS : assigned\_to

&#x20;   PARKING\_RECORDS ||--o| PAYMENTS : has



&#x20;   USERS {

&#x20;       bigint id PK

&#x20;       string name

&#x20;       string email UK

&#x20;       string password

&#x20;       string role

&#x20;       boolean is\_active

&#x20;   }



&#x20;   VEHICLES {

&#x20;       bigint id PK

&#x20;       string plate\_number UK

&#x20;       string owner\_name

&#x20;       enum vehicle\_type

&#x20;   }



&#x20;   PARKING\_SLOTS {

&#x20;       bigint id PK

&#x20;       string slot\_number UK

&#x20;       enum slot\_type

&#x20;       enum status

&#x20;   }



&#x20;   PARKING\_RECORDS {

&#x20;       bigint id PK

&#x20;       bigint vehicle\_id FK

&#x20;       bigint parking\_slot\_id FK

&#x20;       datetime entry\_time

&#x20;       datetime exit\_time

&#x20;       decimal parking\_fee

&#x20;       enum status

&#x20;   }



&#x20;   PAYMENTS {

&#x20;       bigint id PK

&#x20;       bigint parking\_record\_id FK

&#x20;       decimal amount

&#x20;       string payment\_method

&#x20;       datetime paid\_at

&#x20;   }



&#x20;   AUDIT\_LOGS {

&#x20;       bigint id PK

&#x20;       bigint user\_id FK

&#x20;       string action

&#x20;       text description

&#x20;       string ip\_address

&#x20;   }

```



\## Relationship Notes



\- A vehicle can have multiple parking records over time.

\- A parking slot can be referenced by multiple parking records over time.

\- Each payment belongs to one parking record. A parking record can have at most one payment.

\- A user can generate multiple audit logs. An audit log may have no associated user.

\- Primary keys (PK), foreign keys (FK), and unique fields (UK) follow the database migrations.

```
