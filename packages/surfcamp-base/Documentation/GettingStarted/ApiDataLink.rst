:navigation-title: API Data Link (CE)

..  _api-data-link:

============
API Data Link - Content Element
============

..  _creating-api-data-link-content-element:

Create a new API Data Link content element
=======================================

To create a new API Data Link content element, go to the `Page` module, click `Create new content` and select the
`API Data Link` from the `API` tab in the Wizard.

.. figure:: /Images/GettingStarted/ApiDataLink/create-api-data-link-content-element.png
      :width: 80%

Choose the `Api Endpoint` which you want to use.

.. figure:: /Images/GettingStarted/ApiDataLink/select-api-endpoint.png
      :width: 80%

For the `Template`, click the `Create new relation` button and select or upload the `Fluid template` you want to use.

.. figure:: /Images/GettingStarted/ApiDataLink/select-template.png
      :width: 80%

.. note::
    The template must be a Fluid template and must contain the variables defined in the `API field mapping` record.
    The template must also be a valid HTML file and must not contain any PHP code.
    The template will be rendered using the Fluid templating engine and the API data will be passed to the template as variables.

For an example of a simple Fluid template, see the `Basic Fluid Template example <basic-fluid-template-example_>`_.
