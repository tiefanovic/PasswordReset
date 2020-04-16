# Password reset by codes
This module provides rest api to reset customer's password by generating reset password code and send it to customer's email

### Sequence
1- customer request password reset `V1/passwords/reset`, The customer will receive email with new code

2- Use `V1/passwords/validate` to validate the customer entered code with his email.

3- use `V1/passwords/change` to change customer password


### Setup

`bin/magento setup:upgrade`

`bin/magento cache:flush`
