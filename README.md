Codeigiter HMVC extension.
===============

```
 +
 | --- + application/
 | --- | --- + core/
 | --- | --- | --- + MY_Loader.php
 | --- | --- | --- + MY_Router.php
 | --- | --- + third_party/
 | --- | --- | --- + HMVC/
 | --- | --- | --- + Loader.php
 | --- | --- | --- + Module.php
 | --- | --- | --- + Router.php
```

How to use codeigniter HMVC extension!

How to load Module:
```
$adminModule = $this->load->module('admin');
```

How to get instance of module controller:
```
$moduleController = $this->load->module('admin')->controller('someController');
```

How to call module controller methods:
```
$this->load->module('admin')->controller('someController')->someFunction();
```

```
$moduleController = $this->load->module('admin')->controller('someController');
$moduleController->someFunction();
```

How to load module model:
```
$this->load->module('admin')->model('testmodel', 'RenamedTestModel');
$this->RenamedTestModel;//contain instance of testmodel model
```

How to get instance of module model:
```
$returnedModel = $this->load->module('admin')->getModel('testmodel');
```

How to call model methods:
```
$this->load->module('admin')->getModel('testmodel')->someFunction();
```

```
$modelInstance = $this->load->module('admin')->getModel('testmodel');
$modelInstance->someFunction();
```

