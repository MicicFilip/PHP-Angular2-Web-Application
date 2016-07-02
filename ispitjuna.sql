drop table if exists KATEGORIJA_PROIZVODA;

drop table if exists KORISNICI;

drop table if exists PORUDZBINA;

drop table if exists PROIZVOD;

/*==============================================================*/
/* Table: KATEGORIJA_PROIZVODA                                  */
/*==============================================================*/
create table KATEGORIJA_PROIZVODA
(
   ID                   int not null auto_increment,
   IME                  varchar(128) not null,
   primary key (ID)
);

/*==============================================================*/
/* Table: KORISNICI                                             */
/*==============================================================*/
create table KORISNICI
(
   ID                   int not null auto_increment,
   FIRSTNAME            varchar(128) not null,
   LASTNAME             varchar(128) not null,
   USERNAME             varchar(50) not null,
   PASSWORD             varchar(256) not null,
   TOKEN                varchar(256),
   primary key (ID)
);

/*==============================================================*/
/* Table: PORUDZBINA                                            */
/*==============================================================*/
create table PORUDZBINA
(
   ID                   int not null auto_increment,
   KORISNICI_ID         int,
   PROIZVOD_ID          int,
   DATUM                datetime,
   primary key (ID)
);

/*==============================================================*/
/* Table: PROIZVOD                                              */
/*==============================================================*/
create table PROIZVOD
(
   ID                   int not null auto_increment,
   KATEGORIJA_PROIZVODA_ID int,
   IME                  varchar(128) not null,
   CENA                 decimal(12,4) not null,
   OPIS                 varchar(256),
   primary key (ID)
);

alter table PORUDZBINA add constraint FK_RELATIONSHIP_2 foreign key (KORISNICI_ID)
      references KORISNICI (ID) on delete restrict on update restrict;

alter table PORUDZBINA add constraint FK_RELATIONSHIP_3 foreign key (PROIZVOD_ID)
      references PROIZVOD (ID) on delete restrict on update restrict;

alter table PROIZVOD add constraint FK_RELATIONSHIP_1 foreign key (KATEGORIJA_PROIZVODA_ID)
      references KATEGORIJA_PROIZVODA (ID) on delete restrict on update restrict;
