# Magento Custom Email

# Example
```
$data = [
            'comment' => 'Hello this is a test message',
            'email' => 'john.doe@domain.com',
            'name' => 'John Doe',
        ];

        /**
         * wrap parameters values in array as they are called by call_user_func_array
         */
        $config = [
            'template_identifier' => ['contact_us'],
            'template_options' => [['area' => Area::AREA_FRONTEND ,'store' => $this->storeManager->getStore()->getId()]],
            'template_vars' => [$data],
            'from' => [['email' => 'john.doe@domain.com', 'name' => 'John Doe']],
            'to' => ['email' =>'tom.right@example.com', 'name' => 'Tom Right'],
            'cc' => ['email' =>'sarah.foxon@yahoo.com', 'name' => 'Sarah Foxon'],
            'bcc' => ['email' =>'Ahmed.Hassan@example.com', 'name' => 'Ahmed Hassan'],
        ];

        $this->sendEmail->setConfig($config);
        $this->sendEmail->send();
```
# Template Config
app/code/Namespace/Modulename/etc/email_templates.xml

```
<?xml version="1.0" encoding="UTF-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:Email/etc/email_templates.xsd">
    <template id="contact_us" label="Contact Us" file="contactus.html" type="html" module="Namespace_Modulename" area="frontend"/>
</config>

```

# Template
app/code/Namespace/Modulename/view/frontend/email/contactus.html

```
<!--@subject "Contact Us" @-->
<p>Name: {{var name}}</p>
<p>Email: {{var email}}</p>
<p>Comment: {{var comment}}</p>
```