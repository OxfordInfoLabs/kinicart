
-- Account table.
CREATE TABLE kc_setting (
    account_id INTEGER,
    key VARCHAR(255),
    value TEXT,
    value_index INTEGER,
    PRIMARY KEY (account_id, key, value_index)
);

