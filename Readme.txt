Introduction
============

This is a simple timesheet application based on the FLOW3 PHP Framework.
It's my first experience with a real world application in FLOW3 so if you have ANY idea on how
to solve something better, feel free to contact me!

Setup
=====

Clone this into Package/Application/VS.TimeSheet/ folder and continue using the FLOW3 CLI:

1) Create/Update Database

Update:
./flow3 doctrine:update

Create: (If it's a fresh FLOW3 installation)
./flow3 doctrine:create


2) Create one or more accounts
./flow3 timesheet:createaccount <username> <password> <firstName> <lastName> (<role[Employee|Management|Administrator]>)

* Default role is Employee

