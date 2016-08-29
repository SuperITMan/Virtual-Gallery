# Virtual-Gallery

Administration for managing website. 
Used to upload images, create products, etc.

### Run the Docker machine
> docker run -it -d \

> --name <container_name_prefix>-admin \
  
> -v <volume_upload_location>:/var/www/public/uploads \
  
> -v <volume_config_location>:/var/www/config \
  
> -v <log_location>/<container_name_prefix>-admin:/var/log/apache2 \
  
> --link <container_name_prefix>-database:db \
  
> -e "VIRTUAL_HOST="<domain_name_admin> \
  
> -e "LETSENCRYPT_TEST="<is_development_project>(true/false) \
  
> -e "LETSENCRYPT_HOST="<domain_name_admin> \
  
> -e "LETSENCRYPT_EMAIL="<your_email_address> \
  
> --restart="always" \
  
> superitman/virtual-gallery:admin