# http://code.google.com/p/apns-php/wiki/CertificateCreation
#
#
# /opt/wget/wget https://www.entrust.net/downloads/binary/entrust_2048_ca.cer -O - > entrust_root_certification_authority.pem
#
#
#
#openssl pkcs12 -in server_certificates_bundle_sandbox.p12 -out server_certificates_bundle_sandbox.pem -nodes -clcerts
#

openssl pkcs12 -in ${1}.p12 -out ${1}.pem -nodes -clcerts

