The files herein are used by Realistic dummy content to replace generated
content for fields in entities of type node. There will be subdirectories in
here, one for each "bundle" (or content type).

For example if you have the same fields in two content types, you can add
a symbolic link from one to another:

    cd realistic_dummy_content/fields/node
    ln -s one_type another_type

You can do the same if you want to have the same dummy content in, say field_image in one content type as in field_image in another:

    cd realistic_dummy_content/fields/my_content_type
    ln -s ../article/field_image field_image
