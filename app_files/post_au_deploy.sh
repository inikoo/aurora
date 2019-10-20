sudo chgrp -R www-data au


for ACCOUNT in 'instance_1' ... 'instance_n'
do
cd au
rsync -avzh assets /home/inikoo/$ACCOUNT
rsync -avzh EcomB2B/assets /home/inikoo/$ACCOUNT/EcomB2B/
cd ..
rsync -avzh au/* /home/inikoo/$ACCOUNT/
done