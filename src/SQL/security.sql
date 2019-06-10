DROP TABLE IF EXISTS kc_role;

-- Role table
CREATE TABLE IF NOT EXISTS kc_role (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id  INTEGER,
    scope VARCHAR(20),
    name    VARCHAR(50),
    description VARCHAR(255),
    privileges LONGTEXT
);



DROP TABLE IF EXISTS kc_user;

-- User table.
CREATE TABLE IF NOT EXISTS kc_user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email_address   VARCHAR(255),
    name VARCHAR(255),
    parent_account_id INTEGER DEFAULT 0,
    hashed_password  VARCHAR(50),
    mobile_number   VARCHAR(50),
    backup_email_address    VARCHAR(255),
    two_factor_data TEXT,
    active_account_id   INTEGER,
    status  VARCHAR(20)
);



DROP TABLE IF EXISTS kc_user_role;

-- User account roles relational table
CREATE TABLE IF NOT EXISTS kc_user_role (
    user_id INTEGER,
    scope  VARCHAR(100),
    scope_id    INTEGER,
    role_id INTEGER,
    PRIMARY KEY (user_id, scope, scope_id, role_id)
);



-- Create the role join view for optimised querying for users.
DROP VIEW IF EXISTS kc_vw_user_role;

CREATE VIEW kc_vw_user_role AS
    SELECT uar.*, r.privileges, a.status account_status
        FROM kc_user_role uar
        LEFT JOIN kc_role r ON uar.role_id = r.id
        LEFT JOIN kc_account a ON uar.scope = 'ACCOUNT' AND uar.scope_id = a.account_id;


