<?php
$CONFIG = array (
  'htaccess.RewriteBase' => '/',
  'memcache.local' => '\\OC\\Memcache\\APCu',
  'apps_paths' => 
  array (
    0 => 
    array (
      'path' => '/var/www/html/apps',
      'url' => '/apps',
      'writable' => false,
    ),
    1 => 
    array (
      'path' => '/var/www/html/custom_apps',
      'url' => '/custom_apps',
      'writable' => true,
    ),
  ),
  'instanceid' => 'ocn1q15hdqa5',
  'passwordsalt' => 'tvZyLPANURMxPpcCmGPazv+i/Kpq4Y',
  'secret' => 'WgnMPYv6TxkjwpcDWVbddaE8ilu8Vne1EKSUVkLzvsWpjvmR',
  'trusted_domains' => 
  array (
    0 => 'localhost:9080',
    1 => '192.168.0.117:9080',
  ),
  'datadirectory' => '/var/www/html/data',
  'overwrite.cli.url' => 'http://localhost:9080',
  'dbtype' => 'sqlite3',
  'version' => '13.0.2.1',
  'dbname' => 'nextclouddb',
  'dbhost' => 'localhost',
  'dbport' => '',
  'dbtableprefix' => 'oc_',
  'installed' => true,
  'mail_smtpmode' => 'smtp',
  'mail_smtpauthtype' => 'LOGIN',
  'mail_from_address' => 'tushar.kokane91',
  'mail_domain' => 'gmail.com',
  'mail_smtpauth' => 1,
  'mail_smtpport' => '587',
  'mail_smtpname' => 'tushar.kokane91@gmail.com',
  'mail_smtppassword' => '9422188109',
  'mail_smtphost' => 'smtp.gmail.com',
  'mail_smtpsecure' => 'tls',
  'ldapIgnoreNamingRules' => false,
  'knowledgebaseenabled' => false,
  'ldapProviderFactory' => '\\OCA\\User_LDAP\\LDAPProviderFactory',
  'maintenance' => false,
);
