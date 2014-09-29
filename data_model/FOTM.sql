--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.3
-- Dumped by pg_dump version 9.2.3
-- Started on 2014-09-22 15:18:28

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 187 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2094 (class 0 OID 0)
-- Dependencies: 187
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 177 (class 1259 OID 16652)
-- Name: Ads_To_Runaways; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Ads_To_Runaways" (
    "Ads_To_RunawaysID" uuid NOT NULL,
    "RunawayAdID" uuid NOT NULL,
    "RunawayID" uuid NOT NULL
);


ALTER TABLE public."Ads_To_Runaways" OWNER TO postgres;

--
-- TOC entry 169 (class 1259 OID 16405)
-- Name: Cities; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Cities" (
    "CityID" uuid NOT NULL,
    "Name" character varying(50),
    "StateID" uuid NOT NULL,
    "RefID" character varying,
    "PolyCoords" polygon,
    "Authority" character varying
);


ALTER TABLE public."Cities" OWNER TO postgres;

--
-- TOC entry 2095 (class 0 OID 0)
-- Dependencies: 169
-- Name: COLUMN "Cities"."RefID"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Cities"."RefID" IS 'this is to store the URI for the web service we may rely upon';


--
-- TOC entry 2096 (class 0 OID 0)
-- Dependencies: 169
-- Name: COLUMN "Cities"."PolyCoords"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Cities"."PolyCoords" IS 'to store geo polygon, should the need arise';


--
-- TOC entry 2097 (class 0 OID 0)
-- Dependencies: 169
-- Name: COLUMN "Cities"."Authority"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Cities"."Authority" IS 'Should this be tracked in another table?';


--
-- TOC entry 179 (class 1259 OID 24666)
-- Name: Colors; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Colors" (
    "ColorID" uuid NOT NULL,
    "ColorName" character varying
);


ALTER TABLE public."Colors" OWNER TO postgres;

--
-- TOC entry 170 (class 1259 OID 16418)
-- Name: Counties; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Counties" (
    "CountyID" uuid NOT NULL,
    "StateID" uuid NOT NULL,
    "Name" character varying(50) NOT NULL,
    "PolyCoords" polygon,
    "RefID" character varying,
    "Authority" character varying
);


ALTER TABLE public."Counties" OWNER TO postgres;

--
-- TOC entry 2098 (class 0 OID 0)
-- Dependencies: 170
-- Name: COLUMN "Counties"."PolyCoords"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Counties"."PolyCoords" IS 'stores geo codes, if the need should arise';


--
-- TOC entry 174 (class 1259 OID 16505)
-- Name: NewspaperEditions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "NewspaperEditions" (
    "NewspaperEditionID" uuid NOT NULL,
    "NewspaperID" uuid NOT NULL,
    "DistributionDate" date,
    "UniqueIssueID" character varying,
    "SourceURL" character varying,
    "Analogue" character varying
);


ALTER TABLE public."NewspaperEditions" OWNER TO postgres;

--
-- TOC entry 2099 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN "NewspaperEditions"."UniqueIssueID"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "NewspaperEditions"."UniqueIssueID" IS 'from spreadsheet, a field consisting of the concatenation of the year, day number, month and (state?)';


--
-- TOC entry 171 (class 1259 OID 16431)
-- Name: Newspapers; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Newspapers" (
    "NewspaperID" uuid NOT NULL,
    "CityID" uuid NOT NULL,
    "CountyID" uuid,
    "StateID" uuid NOT NULL,
    "Name" character varying
);


ALTER TABLE public."Newspapers" OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 16697)
-- Name: OCR_Revisions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "OCR_Revisions" (
    "RevisionID" uuid NOT NULL,
    "RevisionDate" date,
    "RevisionVersion" character varying,
    "RunawayAdID" uuid NOT NULL,
    "RevisionText" character varying,
    "PersonID" uuid NOT NULL,
    "Metadata" xml
);


ALTER TABLE public."OCR_Revisions" OWNER TO postgres;

--
-- TOC entry 172 (class 1259 OID 16454)
-- Name: Owners; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Owners" (
    "OwnerID" uuid NOT NULL,
    "F_Name" character varying,
    "L_Name" character varying,
    "StateID" uuid,
    "CountyID" uuid,
    "OwnerPrevFName" character varying(50),
    "OwnerPrevLName" character varying(100)
);


ALTER TABLE public."Owners" OWNER TO postgres;

--
-- TOC entry 2100 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN "Owners"."OwnerPrevLName"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Owners"."OwnerPrevLName" IS 'owner''s previous last name';


--
-- TOC entry 180 (class 1259 OID 24744)
-- Name: Person; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Person" (
    "PersonID" uuid NOT NULL,
    "FirstName" character varying,
    "LastName" character varying,
    "OrganizationName" character varying,
    "DeptField" character varying,
    "Email" character varying,
    "PassHash" character varying NOT NULL,
    "Salt" character varying NOT NULL,
    "DateCreated" date
);


ALTER TABLE public."Person" OWNER TO postgres;

--
-- TOC entry 184 (class 1259 OID 24979)
-- Name: RunawayAd_DupFlags; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "RunawayAd_DupFlags" (
    "RunawayAdDupFlagID" uuid NOT NULL,
    "DateEntered" date,
    "Runaway_AD_ID" uuid,
    "SuspectedDupIDs" uuid[],
    "IsAuthoritative" boolean
);


ALTER TABLE public."RunawayAd_DupFlags" OWNER TO postgres;

--
-- TOC entry 2101 (class 0 OID 0)
-- Dependencies: 184
-- Name: TABLE "RunawayAd_DupFlags"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE "RunawayAd_DupFlags" IS 'This is one way we could handle dedup''ing. The SuspectedDupIDs field is an array of Runaway_AD_IDs. This way we can track who the duplicates might be as well as the the given runaway may be a duplicate of. May warrant more discussion, but I think this may work.';


--
-- TOC entry 2102 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN "RunawayAd_DupFlags"."DateEntered"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayAd_DupFlags"."DateEntered" IS 'This would be the date when the duplicate was entered into the database';


--
-- TOC entry 2103 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN "RunawayAd_DupFlags"."Runaway_AD_ID"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayAd_DupFlags"."Runaway_AD_ID" IS 'This is the id that matches the runaway table';


--
-- TOC entry 2104 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN "RunawayAd_DupFlags"."SuspectedDupIDs"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayAd_DupFlags"."SuspectedDupIDs" IS 'This field contains an array of runaway ids that may be duplicates';


--
-- TOC entry 2105 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN "RunawayAd_DupFlags"."IsAuthoritative"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayAd_DupFlags"."IsAuthoritative" IS 'This is a true/false field indicating whether or not the RunawayID referenced is the ''non-duplicative'' record';


--
-- TOC entry 173 (class 1259 OID 16472)
-- Name: RunawayAds; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "RunawayAds" (
    "Runaway_AD_ID" uuid NOT NULL,
    "RecordNumber" integer,
    "NewspaperEditionID" uuid NOT NULL,
    "Notes" character varying,
    "OriginalImage" bytea,
    "PageNumber" integer,
    "isJailor" boolean
);


ALTER TABLE public."RunawayAds" OWNER TO postgres;

--
-- TOC entry 2106 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN "RunawayAds"."OriginalImage"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayAds"."OriginalImage" IS 'variable-length binary string for storing files';


--
-- TOC entry 2107 (class 0 OID 0)
-- Dependencies: 173
-- Name: COLUMN "RunawayAds"."isJailor"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayAds"."isJailor" IS 'this field is a true/false field indicating whether or not the ad was from a jailor';


--
-- TOC entry 175 (class 1259 OID 16563)
-- Name: RunawayChildren; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "RunawayChildren" (
    "RunawayChildID" uuid NOT NULL,
    "Age" double precision,
    "Gender" character(1),
    "Name" character varying
);


ALTER TABLE public."RunawayChildren" OWNER TO postgres;

--
-- TOC entry 186 (class 1259 OID 25005)
-- Name: RunawayEvents; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "RunawayEvents" (
    "RunawayEventID" uuid NOT NULL,
    "OwnerID" uuid,
    "StateCaughtID" uuid,
    "SoldFromStateID" uuid,
    "SoldFromCountyID" uuid,
    "SoldFromCityID" uuid,
    "DateEntered" date,
    "SlaveWearingDesc" character varying(500),
    "SlaveLanguageSpoken" character varying(100),
    "WasSlaveRecentlySold" boolean,
    "HeadedDesc" character varying(200),
    "wasHeadedHome" boolean,
    "ranAlone" boolean,
    "RanWithNumber" integer,
    "motherAndChildren" boolean,
    "Ran_Mid-ForcedMigration" boolean,
    "Notes" character varying(1000),
    "SlaveMarksScarsMutilations" character varying(500),
    "isAgeApproximate" boolean,
    "wasCaught" boolean,
    "Reward" double precision,
    "RunawayID" uuid,
    "Runaway_AD_ID" uuid
);


ALTER TABLE public."RunawayEvents" OWNER TO postgres;

--
-- TOC entry 2108 (class 0 OID 0)
-- Dependencies: 186
-- Name: TABLE "RunawayEvents"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE "RunawayEvents" IS 'As distinct from the Runaways and RunawayAds tables, this table captures the temoral aspects of an individual flight.';


--
-- TOC entry 2109 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN "RunawayEvents"."DateEntered"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayEvents"."DateEntered" IS 'this captures the date the record of this event was entered into the database';


--
-- TOC entry 2110 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN "RunawayEvents"."SlaveLanguageSpoken"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayEvents"."SlaveLanguageSpoken" IS 'language spoken at the time of flight';


--
-- TOC entry 2111 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN "RunawayEvents"."HeadedDesc"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayEvents"."HeadedDesc" IS 'description of where slave was headed at the time of this event';


--
-- TOC entry 2112 (class 0 OID 0)
-- Dependencies: 186
-- Name: COLUMN "RunawayEvents"."SlaveMarksScarsMutilations"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "RunawayEvents"."SlaveMarksScarsMutilations" IS 'this may be more appropriately placed in the runaway table, but I thought, since markings may change over time, we''d want to store it in the Events table.';


--
-- TOC entry 185 (class 1259 OID 24992)
-- Name: Runaway_DupFlags; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Runaway_DupFlags" (
    "RunawayDupFlagID" uuid NOT NULL,
    "DateEntered" date,
    "RunawayID" uuid,
    "SuspectedDupIDs" uuid[],
    "IsAuthoritative" boolean
);


ALTER TABLE public."Runaway_DupFlags" OWNER TO postgres;

--
-- TOC entry 2113 (class 0 OID 0)
-- Dependencies: 185
-- Name: TABLE "Runaway_DupFlags"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE "Runaway_DupFlags" IS 'This is one way we could handle dedup''ing. The SuspectedDupIDs field is an array of RunawayIDs. This way we can track who the duplicates might be as well as the the given runaway may be a duplicate of. May warrant more discussion, but I think this may work.';


--
-- TOC entry 2114 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN "Runaway_DupFlags"."DateEntered"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Runaway_DupFlags"."DateEntered" IS 'This would be the date when the duplicate was entered into the database';


--
-- TOC entry 2115 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN "Runaway_DupFlags"."RunawayID"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Runaway_DupFlags"."RunawayID" IS 'This is the id that matches the runaway table';


--
-- TOC entry 2116 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN "Runaway_DupFlags"."SuspectedDupIDs"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Runaway_DupFlags"."SuspectedDupIDs" IS 'This field contains an array of runaway ids that may be duplicates';


--
-- TOC entry 2117 (class 0 OID 0)
-- Dependencies: 185
-- Name: COLUMN "Runaway_DupFlags"."IsAuthoritative"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Runaway_DupFlags"."IsAuthoritative" IS 'This is a true/false field indicating whether or not the RunawayID referenced is the ''non-duplicative'' record';


--
-- TOC entry 176 (class 1259 OID 16573)
-- Name: Runaways; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Runaways" (
    "RunawayID" uuid NOT NULL,
    "SlaveName" character varying,
    "SlaveGender" character(1),
    "SlaveAge" double precision,
    "SlaveHeightInches" double precision,
    "SlaveWeightPounds" double precision,
    "SlaveBuildDesc" character varying,
    "SlaveMarksPhysicalAttributes" character varying,
    "Notes" character varying,
    "Date_Transcription" character varying,
    "ColorID" uuid
);


ALTER TABLE public."Runaways" OWNER TO postgres;

--
-- TOC entry 2118 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN "Runaways"."SlaveName"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Runaways"."SlaveName" IS 'for runaway ads, we do not need to track first and last name - we can track that in the derivative runaways table';


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN "Runaways"."SlaveBuildDesc"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Runaways"."SlaveBuildDesc" IS 'if we have a finite set, we could break this off into another table, otherwise i''ll have to be free text';


--
-- TOC entry 2120 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN "Runaways"."SlaveMarksPhysicalAttributes"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "Runaways"."SlaveMarksPhysicalAttributes" IS 'if we have a finite set, we could break this off into another table, otherwise i''ll have to be free text';


--
-- TOC entry 182 (class 1259 OID 24760)
-- Name: SecurityAnswers; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "SecurityAnswers" (
    "AnswerID" uuid NOT NULL,
    "PersonID" uuid NOT NULL,
    "QuestionID" uuid NOT NULL,
    "AnswerHash" character varying
);


ALTER TABLE public."SecurityAnswers" OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 24752)
-- Name: SecurityQuestions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "SecurityQuestions" (
    "QuestionID" uuid NOT NULL,
    "QuestionText" character varying
);


ALTER TABLE public."SecurityQuestions" OWNER TO postgres;

--
-- TOC entry 183 (class 1259 OID 24844)
-- Name: Slaves_To_Children; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "Slaves_To_Children" (
    "Slaves_To_ChildrenID" uuid NOT NULL,
    "RunawayID" uuid NOT NULL,
    "RunawayChildID" uuid NOT NULL
);


ALTER TABLE public."Slaves_To_Children" OWNER TO postgres;

--
-- TOC entry 168 (class 1259 OID 16397)
-- Name: States; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "States" (
    "StateID" uuid NOT NULL,
    "Abbrev" character varying(2) NOT NULL,
    "Name" character varying(50),
    "RefID" character varying,
    "PolyCoords" polygon,
    "Authority" character varying
);


ALTER TABLE public."States" OWNER TO postgres;

--
-- TOC entry 2121 (class 0 OID 0)
-- Dependencies: 168
-- Name: COLUMN "States"."RefID"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "States"."RefID" IS 'This is to store the id if we should use a web service to populate this table.';


--
-- TOC entry 2122 (class 0 OID 0)
-- Dependencies: 168
-- Name: COLUMN "States"."PolyCoords"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN "States"."PolyCoords" IS 'If, at some point we''d like to store geo coded info for a state''s shape, we can do so here.';


--
-- TOC entry 2077 (class 0 OID 16652)
-- Dependencies: 177
-- Data for Name: Ads_To_Runaways; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Ads_To_Runaways" ("Ads_To_RunawaysID", "RunawayAdID", "RunawayID") FROM stdin;
\.


--
-- TOC entry 2069 (class 0 OID 16405)
-- Dependencies: 169
-- Data for Name: Cities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Cities" ("CityID", "Name", "StateID", "RefID", "PolyCoords", "Authority") FROM stdin;
\.


--
-- TOC entry 2079 (class 0 OID 24666)
-- Dependencies: 179
-- Data for Name: Colors; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Colors" ("ColorID", "ColorName") FROM stdin;
\.


--
-- TOC entry 2070 (class 0 OID 16418)
-- Dependencies: 170
-- Data for Name: Counties; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Counties" ("CountyID", "StateID", "Name", "PolyCoords", "RefID", "Authority") FROM stdin;
\.


--
-- TOC entry 2074 (class 0 OID 16505)
-- Dependencies: 174
-- Data for Name: NewspaperEditions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "NewspaperEditions" ("NewspaperEditionID", "NewspaperID", "DistributionDate", "UniqueIssueID", "SourceURL", "Analogue") FROM stdin;
\.


--
-- TOC entry 2071 (class 0 OID 16431)
-- Dependencies: 171
-- Data for Name: Newspapers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Newspapers" ("NewspaperID", "CityID", "CountyID", "StateID", "Name") FROM stdin;
\.


--
-- TOC entry 2078 (class 0 OID 16697)
-- Dependencies: 178
-- Data for Name: OCR_Revisions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "OCR_Revisions" ("RevisionID", "RevisionDate", "RevisionVersion", "RunawayAdID", "RevisionText", "PersonID", "Metadata") FROM stdin;
\.


--
-- TOC entry 2072 (class 0 OID 16454)
-- Dependencies: 172
-- Data for Name: Owners; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Owners" ("OwnerID", "F_Name", "L_Name", "StateID", "CountyID", "OwnerPrevFName", "OwnerPrevLName") FROM stdin;
\.


--
-- TOC entry 2080 (class 0 OID 24744)
-- Dependencies: 180
-- Data for Name: Person; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Person" ("PersonID", "FirstName", "LastName", "OrganizationName", "DeptField", "Email", "PassHash", "Salt", "DateCreated") FROM stdin;
\.


--
-- TOC entry 2084 (class 0 OID 24979)
-- Dependencies: 184
-- Data for Name: RunawayAd_DupFlags; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "RunawayAd_DupFlags" ("RunawayAdDupFlagID", "DateEntered", "Runaway_AD_ID", "SuspectedDupIDs", "IsAuthoritative") FROM stdin;
\.


--
-- TOC entry 2073 (class 0 OID 16472)
-- Dependencies: 173
-- Data for Name: RunawayAds; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "RunawayAds" ("Runaway_AD_ID", "RecordNumber", "NewspaperEditionID", "Notes", "OriginalImage", "PageNumber", "isJailor") FROM stdin;
\.


--
-- TOC entry 2075 (class 0 OID 16563)
-- Dependencies: 175
-- Data for Name: RunawayChildren; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "RunawayChildren" ("RunawayChildID", "Age", "Gender", "Name") FROM stdin;
\.


--
-- TOC entry 2086 (class 0 OID 25005)
-- Dependencies: 186
-- Data for Name: RunawayEvents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "RunawayEvents" ("RunawayEventID", "OwnerID", "StateCaughtID", "SoldFromStateID", "SoldFromCountyID", "SoldFromCityID", "DateEntered", "SlaveWearingDesc", "SlaveLanguageSpoken", "WasSlaveRecentlySold", "HeadedDesc", "wasHeadedHome", "ranAlone", "RanWithNumber", "motherAndChildren", "Ran_Mid-ForcedMigration", "Notes", "SlaveMarksScarsMutilations", "isAgeApproximate", "wasCaught", "Reward", "RunawayID", "Runaway_AD_ID") FROM stdin;
\.


--
-- TOC entry 2085 (class 0 OID 24992)
-- Dependencies: 185
-- Data for Name: Runaway_DupFlags; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Runaway_DupFlags" ("RunawayDupFlagID", "DateEntered", "RunawayID", "SuspectedDupIDs", "IsAuthoritative") FROM stdin;
\.


--
-- TOC entry 2076 (class 0 OID 16573)
-- Dependencies: 176
-- Data for Name: Runaways; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Runaways" ("RunawayID", "SlaveName", "SlaveGender", "SlaveAge", "SlaveHeightInches", "SlaveWeightPounds", "SlaveBuildDesc", "SlaveMarksPhysicalAttributes", "Notes", "Date_Transcription", "ColorID") FROM stdin;
\.


--
-- TOC entry 2082 (class 0 OID 24760)
-- Dependencies: 182
-- Data for Name: SecurityAnswers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "SecurityAnswers" ("AnswerID", "PersonID", "QuestionID", "AnswerHash") FROM stdin;
\.


--
-- TOC entry 2081 (class 0 OID 24752)
-- Dependencies: 181
-- Data for Name: SecurityQuestions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "SecurityQuestions" ("QuestionID", "QuestionText") FROM stdin;
\.


--
-- TOC entry 2083 (class 0 OID 24844)
-- Dependencies: 183
-- Data for Name: Slaves_To_Children; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "Slaves_To_Children" ("Slaves_To_ChildrenID", "RunawayID", "RunawayChildID") FROM stdin;
\.


--
-- TOC entry 2068 (class 0 OID 16397)
-- Dependencies: 168
-- Data for Name: States; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "States" ("StateID", "Abbrev", "Name", "RefID", "PolyCoords", "Authority") FROM stdin;
\.


--
-- TOC entry 2022 (class 2606 OID 16656)
-- Name: PK_Ads_To_RunawaysID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Ads_To_Runaways"
    ADD CONSTRAINT "PK_Ads_To_RunawaysID" PRIMARY KEY ("Ads_To_RunawaysID");


--
-- TOC entry 2032 (class 2606 OID 24767)
-- Name: PK_AnswerID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "SecurityAnswers"
    ADD CONSTRAINT "PK_AnswerID" PRIMARY KEY ("AnswerID");


--
-- TOC entry 2006 (class 2606 OID 16412)
-- Name: PK_CityID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Cities"
    ADD CONSTRAINT "PK_CityID" PRIMARY KEY ("CityID");


--
-- TOC entry 2026 (class 2606 OID 24673)
-- Name: PK_ColorID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Colors"
    ADD CONSTRAINT "PK_ColorID" PRIMARY KEY ("ColorID");


--
-- TOC entry 2008 (class 2606 OID 16425)
-- Name: PK_CountyID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Counties"
    ADD CONSTRAINT "PK_CountyID" PRIMARY KEY ("CountyID");


--
-- TOC entry 2016 (class 2606 OID 16512)
-- Name: PK_NewspaperEditionID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "NewspaperEditions"
    ADD CONSTRAINT "PK_NewspaperEditionID" PRIMARY KEY ("NewspaperEditionID");


--
-- TOC entry 2010 (class 2606 OID 16438)
-- Name: PK_NewspaperID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Newspapers"
    ADD CONSTRAINT "PK_NewspaperID" PRIMARY KEY ("NewspaperID");


--
-- TOC entry 2012 (class 2606 OID 16461)
-- Name: PK_OwnerID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Owners"
    ADD CONSTRAINT "PK_OwnerID" PRIMARY KEY ("OwnerID");


--
-- TOC entry 2028 (class 2606 OID 24751)
-- Name: PK_PersonID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Person"
    ADD CONSTRAINT "PK_PersonID" PRIMARY KEY ("PersonID");


--
-- TOC entry 2030 (class 2606 OID 24759)
-- Name: PK_QuestionID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "SecurityQuestions"
    ADD CONSTRAINT "PK_QuestionID" PRIMARY KEY ("QuestionID");


--
-- TOC entry 2024 (class 2606 OID 16704)
-- Name: PK_RevisionID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "OCR_Revisions"
    ADD CONSTRAINT "PK_RevisionID" PRIMARY KEY ("RevisionID");


--
-- TOC entry 2018 (class 2606 OID 16567)
-- Name: PK_RunawayChildID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "RunawayChildren"
    ADD CONSTRAINT "PK_RunawayChildID" PRIMARY KEY ("RunawayChildID");


--
-- TOC entry 2020 (class 2606 OID 16580)
-- Name: PK_RunawayID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Runaways"
    ADD CONSTRAINT "PK_RunawayID" PRIMARY KEY ("RunawayID");


--
-- TOC entry 2014 (class 2606 OID 16522)
-- Name: PK_Runaway_AD_ID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "RunawayAds"
    ADD CONSTRAINT "PK_Runaway_AD_ID" PRIMARY KEY ("Runaway_AD_ID");


--
-- TOC entry 2034 (class 2606 OID 24848)
-- Name: PK_Slaves_To_ChildrenID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Slaves_To_Children"
    ADD CONSTRAINT "PK_Slaves_To_ChildrenID" PRIMARY KEY ("Slaves_To_ChildrenID");


--
-- TOC entry 2004 (class 2606 OID 16401)
-- Name: PK_StateID; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "States"
    ADD CONSTRAINT "PK_StateID" PRIMARY KEY ("StateID");


--
-- TOC entry 2036 (class 2606 OID 24986)
-- Name: pk_runawayaddupflagid; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "RunawayAd_DupFlags"
    ADD CONSTRAINT pk_runawayaddupflagid PRIMARY KEY ("RunawayAdDupFlagID");


--
-- TOC entry 2038 (class 2606 OID 24999)
-- Name: pk_runawaydupflagid; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Runaway_DupFlags"
    ADD CONSTRAINT pk_runawaydupflagid PRIMARY KEY ("RunawayDupFlagID");


--
-- TOC entry 2040 (class 2606 OID 25017)
-- Name: pk_runawayeventid; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "RunawayEvents"
    ADD CONSTRAINT pk_runawayeventid PRIMARY KEY ("RunawayEventID");


--
-- TOC entry 2046 (class 2606 OID 25138)
-- Name: CountyID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Owners"
    ADD CONSTRAINT "CountyID" FOREIGN KEY ("CountyID") REFERENCES "Counties"("CountyID");


--
-- TOC entry 2043 (class 2606 OID 16677)
-- Name: FK_CityID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Newspapers"
    ADD CONSTRAINT "FK_CityID" FOREIGN KEY ("CityID") REFERENCES "Cities"("CityID");


--
-- TOC entry 2050 (class 2606 OID 25048)
-- Name: FK_Color; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Runaways"
    ADD CONSTRAINT "FK_Color" FOREIGN KEY ("ColorID") REFERENCES "Colors"("ColorID");


--
-- TOC entry 2044 (class 2606 OID 16682)
-- Name: FK_CountyID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Newspapers"
    ADD CONSTRAINT "FK_CountyID" FOREIGN KEY ("CountyID") REFERENCES "Counties"("CountyID");


--
-- TOC entry 2048 (class 2606 OID 24798)
-- Name: FK_NewspaperEditionID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayAds"
    ADD CONSTRAINT "FK_NewspaperEditionID" FOREIGN KEY ("NewspaperEditionID") REFERENCES "NewspaperEditions"("NewspaperEditionID");


--
-- TOC entry 2049 (class 2606 OID 24576)
-- Name: FK_NewspaperID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "NewspaperEditions"
    ADD CONSTRAINT "FK_NewspaperID" FOREIGN KEY ("NewspaperID") REFERENCES "Newspapers"("NewspaperID");


--
-- TOC entry 2055 (class 2606 OID 24768)
-- Name: FK_PersonID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "SecurityAnswers"
    ADD CONSTRAINT "FK_PersonID" FOREIGN KEY ("PersonID") REFERENCES "Person"("PersonID");


--
-- TOC entry 2053 (class 2606 OID 24788)
-- Name: FK_PersonID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "OCR_Revisions"
    ADD CONSTRAINT "FK_PersonID" FOREIGN KEY ("PersonID") REFERENCES "Person"("PersonID");


--
-- TOC entry 2056 (class 2606 OID 24773)
-- Name: FK_QuestionID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "SecurityAnswers"
    ADD CONSTRAINT "FK_QuestionID" FOREIGN KEY ("QuestionID") REFERENCES "SecurityQuestions"("QuestionID");


--
-- TOC entry 2051 (class 2606 OID 16657)
-- Name: FK_RunawayAdID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Ads_To_Runaways"
    ADD CONSTRAINT "FK_RunawayAdID" FOREIGN KEY ("RunawayAdID") REFERENCES "RunawayAds"("Runaway_AD_ID");


--
-- TOC entry 2054 (class 2606 OID 24793)
-- Name: FK_RunawayAdID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "OCR_Revisions"
    ADD CONSTRAINT "FK_RunawayAdID" FOREIGN KEY ("RunawayAdID") REFERENCES "RunawayAds"("Runaway_AD_ID");


--
-- TOC entry 2058 (class 2606 OID 24854)
-- Name: FK_RunawayChildID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Slaves_To_Children"
    ADD CONSTRAINT "FK_RunawayChildID" FOREIGN KEY ("RunawayChildID") REFERENCES "RunawayChildren"("RunawayChildID");


--
-- TOC entry 2052 (class 2606 OID 16662)
-- Name: FK_RunawayID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Ads_To_Runaways"
    ADD CONSTRAINT "FK_RunawayID" FOREIGN KEY ("RunawayID") REFERENCES "Runaways"("RunawayID");


--
-- TOC entry 2057 (class 2606 OID 24849)
-- Name: FK_RunawayID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Slaves_To_Children"
    ADD CONSTRAINT "FK_RunawayID" FOREIGN KEY ("RunawayID") REFERENCES "Runaways"("RunawayID");


--
-- TOC entry 2045 (class 2606 OID 16687)
-- Name: FK_StateID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Newspapers"
    ADD CONSTRAINT "FK_StateID" FOREIGN KEY ("StateID") REFERENCES "States"("StateID");


--
-- TOC entry 2041 (class 2606 OID 24621)
-- Name: FK_StateID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Cities"
    ADD CONSTRAINT "FK_StateID" FOREIGN KEY ("StateID") REFERENCES "States"("StateID");


--
-- TOC entry 2042 (class 2606 OID 24626)
-- Name: FK_StateID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Counties"
    ADD CONSTRAINT "FK_StateID" FOREIGN KEY ("StateID") REFERENCES "States"("StateID");


--
-- TOC entry 2047 (class 2606 OID 25143)
-- Name: FK_StateID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Owners"
    ADD CONSTRAINT "FK_StateID" FOREIGN KEY ("StateID") REFERENCES "States"("StateID");


--
-- TOC entry 2061 (class 2606 OID 25103)
-- Name: fk_ownerid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayEvents"
    ADD CONSTRAINT fk_ownerid FOREIGN KEY ("OwnerID") REFERENCES "Owners"("OwnerID");


--
-- TOC entry 2066 (class 2606 OID 25128)
-- Name: fk_runaway_ad_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayEvents"
    ADD CONSTRAINT fk_runaway_ad_id FOREIGN KEY ("Runaway_AD_ID") REFERENCES "RunawayAds"("Runaway_AD_ID");


--
-- TOC entry 2059 (class 2606 OID 25008)
-- Name: fk_runawayadid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayAd_DupFlags"
    ADD CONSTRAINT fk_runawayadid FOREIGN KEY ("Runaway_AD_ID") REFERENCES "RunawayAds"("Runaway_AD_ID");


--
-- TOC entry 2060 (class 2606 OID 25000)
-- Name: fk_runawayid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "Runaway_DupFlags"
    ADD CONSTRAINT fk_runawayid FOREIGN KEY ("RunawayID") REFERENCES "Runaways"("RunawayID");


--
-- TOC entry 2067 (class 2606 OID 25133)
-- Name: fk_runawayid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayEvents"
    ADD CONSTRAINT fk_runawayid FOREIGN KEY ("RunawayID") REFERENCES "Runaways"("RunawayID");


--
-- TOC entry 2062 (class 2606 OID 25108)
-- Name: fk_soldfromcityid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayEvents"
    ADD CONSTRAINT fk_soldfromcityid FOREIGN KEY ("SoldFromCityID") REFERENCES "Cities"("CityID");


--
-- TOC entry 2063 (class 2606 OID 25113)
-- Name: fk_soldfromcountyid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayEvents"
    ADD CONSTRAINT fk_soldfromcountyid FOREIGN KEY ("SoldFromCountyID") REFERENCES "Counties"("CountyID");


--
-- TOC entry 2064 (class 2606 OID 25118)
-- Name: fk_soldfromstateid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayEvents"
    ADD CONSTRAINT fk_soldfromstateid FOREIGN KEY ("SoldFromStateID") REFERENCES "States"("StateID");


--
-- TOC entry 2065 (class 2606 OID 25123)
-- Name: fk_statecaughtid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "RunawayEvents"
    ADD CONSTRAINT fk_statecaughtid FOREIGN KEY ("StateCaughtID") REFERENCES "States"("StateID");


--
-- TOC entry 2093 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2014-09-22 15:18:29

--
-- PostgreSQL database dump complete
--

