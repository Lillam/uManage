### Endpoints
There are a variety of endpoints that are accessible via an API (Application Programming Interface) which allows you to 
access the data you might need; these are currently:
- /api/projects
- /api/users
- /api/tasks
- /api/time-logs
- /api/journals
- /api/accounts

### Valid API Keys:
- 2|MQKKp4QX9UhNXnuco0O17KLXthZ0wJe9sVWHKp89

### Logging in:
#### Via cURL:
```bash
# cURL with url passed params
curl -d "email={email}&password={password}" \
     -X POST https://www.umanage.test/api/user/login
     
# cURL with json passed params
curl -H "Content-Type: application/json" \
     -d '{"email": "{email}", "password": "{password}"}' \
     -X POST https://www.umanage.test/api/user/login 
```

### End points Via API Key
```bash
curl -H "Authorization: Bearer {api-key}" https://www.umanage.com/api/{endpoint}
```