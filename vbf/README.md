API to create and get content

## Create / Update Content
key: API key from com_api. See the plg_api_users
```http
POST /index.php?option=com_api&app=vbf&resource=vbf&format=raw&key=<key>
```
OR update an existing faq
```http
POST /index.php?option=com_api&app=vbf&resource=vbf&format=raw&key=<key>&id=:id
```

#### Request Params

| Param Name | Required | Type | Comment  |
| ---------- | -------- | ------- | :---- |
| state    | NO      | INT | 1 = Published (Default) / 0 = Unpublished / -1 = Archived |
| code    | NO      | STRING |        |
| ntitle     | NO      | STRING |        |
| nbody      | NO      | STRING |         |
| title      | NO      | STRING |         |
| content      | NO      | STRING |         |

#### Response Params

| Param Name | Comment |
| ---------- | :------ |
| success | true if the faq was created, false if there was a problem |
| message | Error mesage in case success is false |
| data.results | Array containing a single [Faq Object](#faq-object) in case of success. Empty array in case of failure. |

## Get Faqs List
```http
GET /index.php?option=com_api&app=vbf&resource=vbf&format=raw
```
## Get Faqs List (only Faqs)
```http
GET /index.php?option=com_api&app=vbf&resource=faq&format=raw
```
## Get Notifications List
```http
GET /index.php?option=com_api&app=vbf&resource=notification&format=raw
```
#### Request Params

| Param Name | Required | Comment |
| ---------- | -------- | :------ |
| limit         | NO       | Defaults to 20        | 
| limitstart      | NO      | Defaults to 0        |
| fields         | NO       | Defaults to id, state, code, ntitle, nbody, title, content | 


#### Response Params

| Param Name | Comment |
| ---------- | :------- |
| success | true if the faq was created, false if there was a problem |
| message | Error mesage in case success is false |
| data.results | Array of [Faq Objects](#faq-object) in case of success. Empty array in case of failure. |
| data.total |  Total should be the total count that match the filters, not the total of items in the current set, i.e. if there are 240 faqs matching the filters, and the API returns first 20 then the total should contain 240 not 20. |


## Get Single Faq 
```http
GET /index.php?option=com_api&app=vbf&resource=faq&format=raw&id=:id
```

## Get Single Notification 
```http
GET /index.php?option=com_api&app=vbf&resource=notification&format=raw&id=:id
```

#### Request Params

| Param Name | Required | Comment |
| ---------- | -------- | :------ |
| fields         | NO       | Defaults to id, state, code, title, content | 


#### Response Params

| Param Name | Comment  |
| ---------- | :------- |
| success | true if the request succeeds, false if there was a problem |
| message | Error mesage in case success is false |
| data.results | Array containing a single [Faq Object](#faq-object) in case of success. Empty array in case of failure. |


## Faq Object
The actual contents of the faq object will vary based on which fields are requested, however the below is the list of all possible fields.

```json
{
  "id" : "",
  "state" : "",
  "code" : "",
  "ntitle" : "",
  "nbody" : "",
  "title" : "",
  "content" : ""
}
```
