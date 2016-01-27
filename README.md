# VisorClientAPI

A reusable composer library client for accessing a remote Visor service.

## Usage in PHP

Example:
```
$usfVisorAPI = new \USF\IdM\USFVisorAPI([
    'username' => 'visoruser',
    'password' => 'visorpassword',
    'casurl' => 'https://cas.someorg.org',
    'url' => 'https://someorg.org/cas/employee/'
]); 
// Get the visor info for the specified account identifier
$response = $usfVisorAPI->getVisor('U99999999')
// Get the visor info for the specified account identifier using a proxy emplid
$proxy_response = $usfVisorAPI->getVisor('U99999999','00000012345');
```