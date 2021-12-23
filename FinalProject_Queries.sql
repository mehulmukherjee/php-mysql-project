USE amt6702_431W;


-- Create Table Queries


CREATE TABLE ADMINISTRATOR_TABLE(
ADMIN_ID VARCHAR(10) NOT NULL,
SUPPORT_EMAIL VARCHAR(10) NOT NULL,
PRIMARY KEY(ADMIN_ID)
);

CREATE TABLE ACCOUNT_INFO(
ID VARCHAR(10) NOT NULL,
PASSWORD VARCHAR(20) NOT NULL,
ACCOUNT_TYPE VARCHAR(10) NOT NULL,
ACCOUNT_STATUS VARCHAR(10) NOT NULL,
PRIMARY KEY(ID)
);

CREATE TABLE CUSTOMER_INFO(
USER_ID VARCHAR(10) NOT NULL,
FNAME VARCHAR(10) NOT NULL,
MNAME VARCHAR(10) NOT NULL,
LNAME VARCHAR(10) NOT NULL,
BALANCE DECIMAL(10, 2) NOT NULL CHECK (QUANTITY >= 0),
CURRENCY VARCHAR(3) NOT NULL,
PRIMARY KEY(USER_ID)
);

CREATE TABLE CURRENCY_INFO(
CURRENCY VARCHAR(3) NOT NULL,
RATE DECIMAL(5,2) NOT NULL,
SYMBOL VARCHAR(2) NOT NULL,
PRIMARY KEY(CURRENCY)
);

CREATE TABLE CUSTOMER_PORTFOLIO(
    USER_ID VARCHAR(10) NOT NULL,
    CRYPTO VARCHAR(4) NOT NULL,
    QUANTITY DECIMAL(10,2) NOT NULL CHECK (QUANTITY >= 0),
    AVERAGE_PRICE DECIMAL(10,2) NOT NULL,
    PRIMARY KEY(USER_ID, CRYPTO)
);


CREATE TABLE TRANSACTION_TABLE(
    TRANS_ID INT NOT NULL AUTO_INCREMENT,
    USER_ID VARCHAR(10) NOT NULL,
    TRANS_TYPE VARCHAR(4) NOT NULL,
    PRIMARY KEY(TRANS_ID)
);


CREATE TABLE BUY_ORDER(
    BUY_ID INTEGER NOT NULL,
    CRYPTO VARCHAR(4) NOT NULL,
    AMOUNT DECIMAL(10,2) NOT NULL CHECK(AMOUNT >= 0),
    BUY_PRICE DECIMAL(10,2) NOT NULL,
    TIME DATETIME,
    PRIMARY KEY(BUY_ID)
);


CREATE TABLE SELL_ORDER(
    SELL_ID INTEGER NOT NULL,
    CRYPTO VARCHAR(4),
    AMOUNT DECIMAL(10,2) NOT NULL CHECK(AMOUNT >= 0),
    SELL_PRICE DECIMAL(10,2),
    TIME DATETIME,
    PRIMARY KEY(SELL_ID)
);

CREATE TABLE CRYPTOCURRENCY_INFO(
    CRYPTO VARCHAR(4) NOT NULL,
    NAME VARCHAR(20) NOT NULL,
    TYPE VARCHAR(50) NOT NULL,
    ABOUT VARCHAR(300) NOT NULL,
    PRICE DECIMAL(10,2) NOT NULL,
    PRIMARY KEY(CRYPTO)
);


-- 5 plus table join

-- For the Transactions Screen for Admin

SELECT * FROM CUSTOMER_INFO CI
JOIN ACCOUNT_INFO AI ON CI.USER_ID = AI.ID
JOIN CURRENCY_INFO CRI ON CRI.CURRENCY = CI.CURRENCY
JOIN TRANSACTION_TABLE TT ON CI.USER_ID = TT.USER_ID
JOIN 
(SELECT BUY_ID AS 'BUYSELL_ID', CRYPTO, AMOUNT, BUY_PRICE AS 'BS_PRICE', TIME FROM BUY_ORDER 
UNION
SELECT SELL_ID AS 'BUYSELL_ID', CRYPTO, AMOUNT, SELL_PRICE AS 'BS_PRICE', TIME FROM SELL_ORDER) BS 
ON TT.TRANS_ID = BS.BUYSELL_ID
JOIN CRYPTOCURRENCY_INFO CRYI ON BS.CRYPTO = CRYI.CRYPTO ORDER BY CI.USER_ID ASC, BS.TIME DESC ;


-- Queries for Inserting Initila Data into Currency and Crypto Currency Tables

INSERT INTO CRYPTOCURRENCY_INFO(CRYPTO, NAME, TYPE, ABOUT, PRICE)
VALUES
("BTC", "Bitcoin", "Decentralized Token", "Bitcoin is a digital currency which operates free of any central control or the oversight of banks or governments", "46889.64"),
("ETH", "Etherium", "Smart Contract Platform", "Ethereum blockchain focuses on running the programming code of any decentralized application", "3787.17"),
("BNB", "Binance Coin", "Exchange based Token", "Binance is an online exchange where users can trade cryptocurrencies. ", "524.27"),
("SOL", "Solana", "Smart Contract Platform", "Solana uses a “proof of stake” system to verify transactions, manage its coin supply and create new coins", "156.36"),
("ADA", "Cardano", "Smart Contract Platform", "Cardano's main applications are in identity management and traceability. ", "1.23"),
("XRP", "XRP", "DeFi", "XRP is the native cryptocurrency for products developed by Ripple Labs. Its products are used for payment settlement, asset exchange, and remittance systems that work more like SWIFT, a service for international money and security transfers used by a network of banks and financial intermediaries.", "0.790156"),
("DOT", "Polkadot", "Smart Contract Platform", "Polkadot enables cross-blockchain transfers of any type of data or asset, not just tokens.", "26.24"),
("DOGE", "Dogecoin", "Meme Token", "Dogecoin is primarily used for tipping users on Reddit and Twitter, but it is also accepted as a method of payment by a few dozen merchants", "0.157833"),
("LUNA", "Terra", "DeFi", "LUNA is a governance and staking token that fuels the whole Terra network.", "54.24");


INSERT INTO CURRENCY_INFO(CURRENCY, RATE, SYMBOL)
VALUES
("USD", "1", "USD"),
("EUR", "0.89", "EUR"),
("GBP", "0.76", "GBP"),
("INR", "75.79", "INR"),
("YEN", "113.62", "INR");

-- The above data is obtained from https://www.coingecko.com/en and https://www.x-rates.co.