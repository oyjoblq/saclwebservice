This is a web service project developed through SOAP protocal.  

Our division uses services from Symplicity (https://www.symplicity.com/). Their system needs to integrate with our student data. The students’ information are scattered across several tables. I created a view in  the database to join in these tables to obtain all required student information.  Based on the protocol Symplicity has suggested, I developed the web service through SOAP protocol to provide data to this company. I also implemented security check to make sure this service only accessible through approved IP addresses.

All sensitive information such as server name, password, username, IP addresses are modified to use faked information.