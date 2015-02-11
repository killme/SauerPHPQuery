#Sauerbraten PHP Query library
Library for querying php servers.

##Usage
To use the library, first instantiate the AsSauerQuery object,
next call ```->query($ip, $port)``` on it. This will return a server object,
with the players already requested.
Optionally you can instantiate a AsSauerMaster object and call ```->update```
to spawn AsSauerServer objects for any server on the list.

##Notes
The unit tests are ment to be used with Yii
