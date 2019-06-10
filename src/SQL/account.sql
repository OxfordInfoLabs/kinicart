-- Account schema for authentication and account management.

DROP TABLE IF EXISTS kc_account;

-- Account table.
CREATE TABLE IF NOT EXISTS kc_account (
    account_id  INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255),
    sub_accounts_enabled  BOOLEAN,
    parent_account_id INTEGER DEFAULT 0,
    api_key VARCHAR(50),
    api_secret VARCHAR(50),
    status  VARCHAR(20)
);



DROP TABLE IF EXISTS kc_contact;

-- Contact table for general application use
CREATE TABLE IF NOT EXISTS kc_contact (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id  INTEGER,
    type VARCHAR(20),
    name VARCHAR(255),
    organisation VARCHAR(255),
    street1 VARCHAR(255),
    street2    VARCHAR(255),
    city    VARCHAR(255),
    county  VARCHAR(255),
    postcode    VARCHAR(255),
    country_code VARCHAR(2),
    telephone_number VARCHAR(255),
    email_address   VARCHAR(255)
);




