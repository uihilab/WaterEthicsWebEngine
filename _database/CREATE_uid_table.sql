-- Table: uid_table

-- DROP TABLE uid_table;

CREATE TABLE uid_table
(
  ip inet NOT NULL, -- The inet type holds an IPv4 or IPv6 host address, and optionally its subnet, all in one field.
  uid character varying NOT NULL,
  t_epoch bigint,
  game_build integer, -- Each game build will have a unique integer
  CONSTRAINT uid_table_pkey PRIMARY KEY (uid)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE uid_table
  OWNER TO {owner name};
COMMENT ON COLUMN uid_table.ip IS 'The inet type holds an IPv4 or IPv6 host address, and optionally its subnet, all in one field.';
COMMENT ON COLUMN uid_table.game_build IS 'Each game build will have a unique integer';

