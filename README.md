# Mailing service

This project is a simple application for uploading users and sending mails. The project includes routes, services, controllers, and APIs.


## Installation

### 1. Clone the repository

```bash
  git clone https://github.com/pureweb-genius/mailing-api-service.git

```
### 2. Seed the database

```bash
  cd database
  php mailing.php
```
### 3. Upload csv file
```bash
POST {URL}/user/upload 
key "csv_file" is required
```
### 4. Send mailings
```bash
GET {URL}/mailing/send
required params :
'mailing_name'
'message'
```










