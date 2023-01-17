-- TURN OFF FORIEGN KEY CHECKS WHEN IMPORTING!!!!!!!

CREATE DATABASE IF NOT EXISTS motueka;
USE motueka;

-- The rooms for the bed and breakfast
DROP TABLE IF EXISTS room;
CREATE TABLE IF NOT EXISTS room (
  roomID int unsigned NOT NULL auto_increment,
  roomname varchar(100) NOT NULL,
  description text default NULL,
  roomtype character default 'D',  
  beds int,
  PRIMARY KEY (roomID)
) AUTO_INCREMENT=1;

INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (2,"Herman","Lorem ipsum dolor sit amet, consectetuer","D",5);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (3,"Scarlett","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur","D",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (4,"Jelani","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam","S",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (5,"Sonya","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.","S",5);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (6,"Miranda","Lorem ipsum dolor sit amet, consectetuer adipiscing","S",4);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (7,"Helen","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.","S",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (8,"Octavia","Lorem ipsum dolor sit amet,","D",3);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (9,"Gretchen","Lorem ipsum dolor sit","D",3);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (10,"Bernard","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer","S",5);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (11,"Dacey","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur","D",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (12,"Preston","Lorem","D",2);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (13,"Dane","Lorem ipsum dolor","S",4);
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (14,"Cole","Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam","S",1);

-- Customers
DROP TABLE IF EXISTS customer;
CREATE TABLE IF NOT EXISTS customer (
  customerID int unsigned NOT NULL auto_increment,
  firstname varchar(50) NOT NULL,
  lastname varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  role tinyint(10) default 0, 
  password varchar(100) NOT NULL default '.',
  PRIMARY KEY (customerID)
) AUTO_INCREMENT=1;

INSERT INTO customer (customerID,firstname,lastname,email) VALUES 
(2,"Desiree","Collier","Maecenas@non.co.uk"),
(3,"Irene","Walker","id.erat.Etiam@id.org"),
(4,"Forrest","Baldwin","eget.nisi.dictum@a.com"),
(5,"Beverly","Sellers","ultricies.sem@pharetraQuisqueac.co.uk"),
(6,"Glenna","Kinney","dolor@orcilobortisaugue.org"),
(7,"Montana","Gallagher","sapien.cursus@ultriciesdignissimlacus.edu"),(8,"Harlan","Lara","Duis@aliquetodioEtiam.edu"),
(9,"Benjamin","King","mollis@Nullainterdum.org"),
(10,"Rajah","Olsen","Vestibulum.ut.eros@nequevenenatislacus.ca"),
(11,"Castor","Kelly","Fusce.feugiat.Lorem@porta.co.uk"),
(12,"Omar","Oconnor","eu.turpis@auctorvelit.co.uk"),
(13,"Porter","Leonard","dui.Fusce@accumsanlaoreet.net"),
(14,"Buckminster","Gaines","convallis.convallis.dolor@ligula.co.uk"),
(15,"Hunter","Rodriquez","ridiculus.mus.Donec@est.co.uk"),
(16,"Zahir","Harper","vel@estNunc.com"),
(17,"Sopoline","Warner","vestibulum.nec.euismod@sitamet.co.uk"),
(18,"Burton","Parrish","consequat.nec.mollis@nequenonquam.org"),
(19,"Abbot","Rose","non@et.ca"),
(20,"Barry","Burks","risus@libero.net");

-- The bookings table for the bed and breakfast
DROP TABLE IF EXISTS booking;
CREATE TABLE IF NOT EXISTS booking (
  bookingID int unsigned NOT NULL auto_increment,
  checkindate date NOT NULL,
  checkoutdate date NOT NULL,
  phone int NOT NULL,
  extras text default NULL,
  review text default NULL,
  customerID int unsigned NOT NULL,
  roomID int unsigned NOT NULL,
  PRIMARY KEY (bookingID),
  FOREIGN KEY (customerID) REFERENCES customer(customerID),
  FOREIGN KEY (roomID) REFERENCES room(roomID)
) AUTO_INCREMENT=1;

-- date format is year month day
INSERT INTO `booking`(`bookingID`, `checkindate`, `checkoutdate`, `phone`, `extras`, `review`, `customerID`, `roomID`) VALUES ('1','1973-02-02','2022-02-28','0212593006','whatevar','more whatevers','2','2');
INSERT INTO `booking`(`bookingID`, `checkindate`, `checkoutdate`, `phone`, `extras`, `review`, `customerID`, `roomID`) VALUES ('2','1973-02-02','2022-02-28','0212593006','tots whatevar','more whatever whatevers','3','3');
INSERT INTO `room` (`roomID`,`roomname`,`description`,`roomtype`,`beds`) VALUES (21,"Harlan","Lorem ipsum dolor sit amet, consectetuer adipiscing","S",1);
INSERT INTO customer (customerID,firstname,lastname,email, password, role) VALUES (26, 'freddy', 'thefrog', 'freddy@thefrog', '$2y$10$cQBVRxJA25.nk9kEkkrUWu7c9DbFwr0Ez9/fTEV08.MvEu.gx8P9a', 0),
(28, 'james', 'tremain', 'james@james.com', '$2y$10$7y8qxSd2e4E5jiD39.64Suh2BqJXCkLh7W.vjn540VHyQKq6x6i9u', 0),
(29, 'joshua', 'administrator', 'joshua@admin.com', '$2y$10$ZebdQA9mjUzrlHLAVHqi3uT3Uxrus60xfgXqn1KGAVdfbhlHygxYq', 9),
(30, 'brian', 'nursery', 'brian@rainbow.com', '$2y$10$AB7bXQS9RuG9jYB7wAfSDunj7/Dyp5/8eyXtL2l0QvLmQGoR0uqei', 9),
(31, 'corina', 'douglas', 'corina@gohaed.com', '$2y$10$pgWC/.fiSc.lS.kW1OTs2.rpzoZRP/36LmeDee62SJVl8ct0XnOg2', 1),
(32, 'rtrtrtrtt', 'ff', 'james@james.com', '$2y$10$eHw5cX/IBNBqDLqMMsfv.uyCxQlkJbJySU8A1H3f2naBDluB.DC26', 1);
