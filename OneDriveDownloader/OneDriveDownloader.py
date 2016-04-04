'''
OneDrive Downloader Script
https://github.com/ShaneC/Tools/OneDriveDownloader/

Connects via OAuth to a given OneDrive account, downloads the files from the account
in their entirety (preserving directory structure), then ZIPs the directory.

This was created with the intent of allowing for full backups to be taken of the
OneDrive account at regular intervals, and then copied to network storage.

Installation of the OneDrive SDK can be done via PIP:
pip install onedrivesdk
'''

import os
import shutil
import threading
import time
import zipfile
import onedrivesdk
from onedrivesdk.helpers import GetAuthCodeServer

def recurse_directory(client, dir, path):
    "Recursively iterates over OneDrive directory structure"
    for item in dir:
        if(item.file is None):
            # Item is a folder
            sub_dir = client.item(drive="me", id=item.id).children.get()
            sub_path = path + item.name + "/"

            if not os.path.exists(sub_path):
                print("Creating directory " + path + item.name)
                os.makedirs(sub_path)

            recurse_directory(client, sub_dir, sub_path)
        else:
            # Item is a file
            print("Downloading " + path + item.name)
            client.item(drive="me", id=item.id).download(path + item.name)
    return

def zip_directory(path, ziph):
    for root, dirs, files in os.walk(path):
        for file in files:
            ziph.write(os.path.join(root, file))

archive_name = raw_input("Specify an archive name: ")

if (os.path.exists("./" + archive_name + ".zip")):
    raise Exception("Archive name already exists.")

# redirect_uri, client_secret, and client_id can be obtained from:
# http://go.microsoft.com/fwlink/p/?LinkId=193157

redirect_uri = ""
client_secret = ""

if not redirect_uri or not client_secret:
    raise Exception("You must specify a redirect_uri and client_secret.\n" + 
        "You can obtain these from http://go.microsoft.com/fwlink/p/?LinkId=193157.")

client = onedrivesdk.get_default_client(
    client_id='<CLIENT_ID>',
    scopes=['wl.signin', 'onedrive.readonly'])

auth_url = client.auth_provider.get_auth_url(redirect_uri)

# This will block until we have the code
code = GetAuthCodeServer.get_auth_code(auth_url, redirect_uri)

client.auth_provider.authenticate(code, redirect_uri, client_secret)

root_folder = client.item(drive="me", id="root").children.get()

# Create temporary local directory for storage of the files
local_dir = "./" + str(time.time())
i = 0;

while(os.path.exists(local_dir) and (i < 5)):
    local_dir = "./" + str(time.time())
    i += 1

if(i >= 5):
    raise Exception("Unable to create temporary directory.")

os.makedirs(local_dir)

recurse_directory(client, root_folder, local_dir + "/")

zip_dst = archive_name + ".zip"

print("Creating ZIP file at " + zip_dst)

zip = zipfile.ZipFile(
    file = zip_dst, 
    mode = 'w', 
    allowZip64 = True);

zip_directory(local_dir, zip)

zip.close()

print("Deleting temp directory...")

shutil.rmtree(local_dir)

print("All done!")
