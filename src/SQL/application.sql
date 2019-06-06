
-- Account table.
CREATE TABLE kc_setting (
    parent_account_id INTEGER,
    key VARCHAR(255),
    value TEXT,
    value_index INTEGER,
    PRIMARY KEY (parent_account_id, key, value_index)
);

