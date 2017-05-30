A recipe can define which dummy content needs to be generated in which order.

To create your own recipe, first copy this file to your own module, and rename it:

    mkdir -p MYMODULE/realistic_dummy_content/recipe
    cp realistic_dummy_content/realistic_dummy_content/recipe/realistic_dummy_content.recipe.inc my_module/realistic_dummy_content/recipe/my_module.recipe.inc

Then rename the class inside my_module.recipe.inc from `realistic_dummy_content_realistic_dummy_content_recipe` to `d8poc_custom_realistic_dummy_content_recipe`.
