-- Account schema for authentication and account management.

-- Account table.
CREATE TABLE kc_account (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255),
    sub_accounts_enabled  BOOLEAN,
    parent_account_id INTEGER,
    status  VARCHAR(20)
);


-- User table.
CREATE TABLE kc_user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email_address   VARCHAR(255),
    name VARCHAR(255),
    context_key VARCHAR(255),
    hashed_password  VARCHAR(50),
    mobile_number   VARCHAR(50),
    backup_email_address    VARCHAR(255),
    two_factor_data TEXT,
    active_account_id   INTEGER,
    status  VARCHAR(20)
);


-- Role table
CREATE TABLE kc_role (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id  INTEGER,
    scope VARCHAR(20),
    role_key    VARCHAR(50),
    description VARCHAR(255),
    privileges LONGTEXT
);

-- User account roles relational table
CREATE TABLE kc_user_account_role (
    user_id INTEGER,
    account_id  INTEGER,
    role_id    INTEGER,
    PRIMARY KEY (user_id, account_id, role_id)
);


-- Contact table for general application use
CREATE TABLE kc_contact (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id  INTEGER,
    type VARCHAR(20),
    name VARCHAR(255),
    organisation VARCHAR(255),
    street_1 VARCHAR(255),
    street_2    VARCHAR(255),
    city    VARCHAR(255),
    county  VARCHAR(255),
    postcode    VARCHAR(255),
    country_code VARCHAR(2),
    telephone_number VARCHAR(255),
    email_address   VARCHAR(255)
);



