:navigation-title: API Configuration

..  _api-configuration:

============
API Configuration
============

..  _creating-api-configuration-record:

Create a new API configuration record
=======================================

To create a new API configuration record, go to the `List` module, click `Create new record` and select
`API Base configuration` from the list of available record types.

.. figure:: /Images/GettingStarted/ApiConfiguration/create-api-configuration-record.png
      :width: 80%

Fill in the `Name` and `API base url` fields. Also provide `Additional headers` if your API requires authentication
or other headers.

.. figure:: /Images/GettingStarted/ApiConfiguration/base-api-configuration.png
      :width: 80%

To add an endpoint, click the `Create new` button on the `API endpoints` field.
Provide the `Name` which is used to identify the endpoint in the `API Data Link` content element,
the `Path` which is the path to be appended to the base url, including parameters, e.g. `teams?name=bayernmünchen`.
The `Cache Lifeftime` is the time in seconds the data is cached and will be defaulted to `0` if no
 value is provided. The `Type` is used to identify the type of the endpoint, e.g. `JSON`, `XML` or `GraphQL`.

.. figure:: /Images/GettingStarted/ApiConfiguration/create-api-endpoint.png
      :width: 80%

To map the API data keys to the Fluid template variables, click the `Create new` button on the `API field mapping` field. Provide the `Source` which is the key from the API data.
If a key is nested inside another key, use the dot notation, e.g. `team.name`. The `Target` is the variable name used in the Fluid template.
The `Type` is used to identify the type of the variable, e.g. `string`, `int`, `float`, `bool`, `array` or `object`.

.. figure:: /Images/GettingStarted/ApiConfiguration/create-api-field-mapping.png
      :width: 80%
