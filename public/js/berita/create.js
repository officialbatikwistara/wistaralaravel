tinymce.init({
    selector: "textarea[name=konten]",
    height: 400,
    menubar: true,
    plugins: "image link media table code lists",
    toolbar:
        "undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | image media link table | code",
    automatic_uploads: true,
    images_upload_url: "/upload",
    file_picker_types: "image",
    content_style: "body { font-family:Poppins, sans-serif; font-size:14px }",
});
