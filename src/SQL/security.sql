
-- Role table
CREATE TABLE kc_role (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id  INTEGER,
    scope VARCHAR(20),
    name    VARCHAR(50),
    description VARCHAR(255),
    privileges LONGTEXT
);
