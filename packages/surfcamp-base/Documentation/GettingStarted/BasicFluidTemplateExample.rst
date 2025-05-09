:navigation-title: Basic Fluid Template example

..  _basic-fluid-template-example:

============
Basic Fluid Template example
============

..  _creating-a-fluid-template:

Create a Fluid template
=======================================
Either create a new Fluid template by creating an `.html` file on your machine or create a new Fluid template via the
`Filelist` module.

To create a new Fluid template via the `Filelist` module, go to the `Filelist` module, click `Create new file`,
provide a `File name` appended with `.html` and click the `Create file` button.

Both options (creating a new file on your machine or via the `Filelist` module) can be used to create a new Fluid template.
In this example, we create a new Fluid template via the `Filelist` module which is able to show the data of a Soccer team.

When creating your own Fluid template, make sure the variables between `{}` match your target mappings.

.. code-block:: html

   <html data-namespace-typo3-fluid="true">
   <f:asset.css identifier="inline">
       * {
           box-sizing: border-box;
           margin: 0;
           padding: 0;
       }

       body {
           font-family: 'Arial', sans-serif;
           background-color: #f4f7f9;
           color: #333;
           line-height: 1.6;
           padding: 20px;
       }

       .container {
           max-width: 900px;
           margin: 0 auto;
       }

       h1 {
           text-align: center;
           margin-bottom: 30px;
           color: #063462;
       }

       .player-list {
           display: grid;
           grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
           gap: 25px;
       }

       .player-card {
           background-color: white;
           border-radius: 10px;
           overflow: hidden;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
           transition: transform 0.3s ease, box-shadow 0.3s ease;
       }

       .player-card:hover {
           transform: translateY(-5px);
           box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
       }

       .player-image {
           width: 100%;
           height: 200px;
           object-fit: cover;
           display: block;
       }

       .player-info {
           padding: 15px;
       }

       .player-name {
           font-size: 18px;
           font-weight: bold;
           margin-bottom: 5px;
           color: #063462;
       }

       .player-position {
           font-size: 14px;
           color: #666;
           background-color: #e9f2fb;
           padding: 3px 8px;
           border-radius: 12px;
           display: inline-block;
           margin-top: 5px;
       }

       @media (max-width: 600px) {
           .player-list {
               grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
               gap: 15px;
           }

           .player-image {
               height: 170px;
           }

           .player-info {
               padding: 10px;
           }

           .player-name {
               font-size: 16px;
           }
       }
   </f:asset.css>
   <div class="container">
       <f:debug>{_all}</f:debug>
       <h1>{data.header}</h1>
       <div class="player-list">
           <f:for as="item" each="{apiValues}">
               <div class="player-card">
                   <f:variable name="image" value="{f:if(condition: '{item.image} != \'\'', then: '{item.image}', else: 'https://media.istockphoto.com/id/1409329028/vector/no-picture-available-placeholder-thumbnail-icon-illustration-design.jpg?s=612x612&w=0&k=20&c=_zOuJu755g2eEUioiOUdz_mHKJQJn-tDgIAhQzyeKUQ=')}"></f:variable>
                   <img class="player-image" src="{image}" alt="{item.name}">
                   <div class="player-info">
                       <div class="player-name">{item.name}</div>
                       <div class="player-position">{item.position}</div>
                   </div>
               </div>
           </f:for>
       </div>
   </div>
   </html>

.. note::
    The Fluid template is a simple HTML template that uses the `f:for` view helper to loop through the API data and display it in a grid layout.
    The `f:variable` view helper is used to set a default image if no image is provided in the API data.
    The CSS styles are included in the template using the `f:asset.css` view helper.

For more information about Fluid templates and it's possibilities, see the `Fluid documentation <https://docs.typo3.org/m/typo3/tutorial-getting-started/main/en-us/Concepts/Fluid/Index.html>`_.

..  _uploading-your-fluid-template:

Uploading your Fluid template
=======================================

If you chose to create a new Fluid template by creating a new file on your machine, you can upload the file to
the `Filelist` module by clicking the `Upload files` button and selecting the file from your machine. (Or do it
directly via the `Api Data Link` content element by clicking the `Create new relation` button and selecting the
file from your machine.)

.. _preview-of-our-fluid-template-in-the-frontend:

Preview of our Fluid template in the frontend
=======================================

By following all steps in `Api Configuration <api-configuration_>`_, `Api Data Link <api-data-link_>`_ and this page
you should be able to see your data and the Fluid template in the frontend:

.. figure:: /Images/GettingStarted/BasicFluidTemplateExample/preview-of-fluid-template.png
   :width: 80%
