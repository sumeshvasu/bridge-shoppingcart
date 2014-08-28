ALTER TABLE  `bs_purchases` ADD  `transactionId` VARCHAR( 250 ) NOT NULL AFTER  `dateTime` ;

ALTER TABLE  `bs_purchases` ADD  `totalPrice` DECIMAL( 10, 2 ) NOT NULL AFTER  `transactionId` ;
