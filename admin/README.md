# Virtual-Gallery

Administration for managing website. 
Used to upload images, create products, etc.

### Run the Docker machine
> docker run -it -d \

> --name &lt;container_name_prefix&gt;-admin \
  
> -v &lt;volume_upload_location&gt;:/var/www/public/uploads \
  
> -v &lt;volume_config_location&gt;:/var/www/config \
  
> -v &lt;log_location&gt;/&lt;container_name_prefix&gt;-admin:/var/log/apache2 \
  
> --link &lt;container_name_prefix&gt;-database:db \
  
> -e "VIRTUAL_HOST="&lt;domain_name_admin&gt; \
  
> -e "LETSENCRYPT_TEST="&lt;is_development_project&gt;(true/false) \
  
> -e "LETSENCRYPT_HOST="&lt;domain_name_admin&gt; \
  
> -e "LETSENCRYPT_EMAIL="&lt;your_email_address&gt; \
  
> --restart="always" \
  
> superitman/virtual-gallery:admin