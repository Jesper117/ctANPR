document.getElementById('car-image').addEventListener('change', function(event) {
    var formData = new FormData();
    var imageFile = event.target.files[0];

    // Display a loading indicator here if you like
    // e.g., document.getElementById('loading').style.display = 'block';

    formData.append('car-image', imageFile);

    // Replace 'your-api-endpoint' with the endpoint URL of your ANPR API
    fetch('your-api-endpoint', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            // Assuming 'data' is an object with a property 'imageUrl' where the processed image can be accessed
            var imagePreview = document.getElementById('image-preview');
            var previewImg = document.getElementById('preview-img');
            var defaultText = document.getElementById('default-text');

            // Hide the default text and show the processed image
            defaultText.style.display = 'none';
            previewImg.src = data.imageUrl; // URL to the processed image with LP overlay
            previewImg.style.display = 'block';

            // Hide the loading indicator here if you added one
            // e.g., document.getElementById('loading').style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
        });
});
