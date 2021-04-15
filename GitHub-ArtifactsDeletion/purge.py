import requests
import json

# https://docs.github.com/en/rest/reference/actions#artifacts

GITHUB_REPO = "https://api.github.com/repos/[owner]/[repo]}"
GITHUB_USER = "{GITHUB USERNAME HERE}"
GITHUB_TOKEN = "{GITHUB TOKEN WITH WORKFLOW PERMISSIONS HERE}"

authHeader = { "Authorization" : "token " + GITHUB_TOKEN }
artifacts = {}
total_number = 0

def load_artifacts():
    global total_number, artifacts

    req = requests.get(GITHUB_REPO + "/actions/artifacts", headers=authHeader)

    total_number = req.json()["total_count"]

    for artifact in req.json()["artifacts"]:
        artifacts[artifact["id"]] = artifact

def delete_artifact(artifact_id):
    global artifacts, GITHUB_REPO, authHeader

    req = requests.delete(GITHUB_REPO + "/actions/artifacts/" + str(artifact_id), headers=authHeader)

    if req.status_code == 204:
        print("[SUCCESS] Artifact " + str(artifact_id) + " deleted [" + str(req.status_code) + "].\n")
    else:
        print("[SUCCESS] Artifact " + str(artifact_id) + " failed to delete [" + str(req.status_code) + "].\n")


# Load artifact data into memory
load_artifacts()

# Main Program Loop
while True:
    print(
        "\n--- MENU ---\n" + 
        "1. List number of artifacts\n" + 
        "2. List all active artifacts\n" + 
        "3. Purge specific artifact by #\n" + 
        "4. Purge all artifacts\n" +
        "5. Exit\n"
    )
    
    choice = input(f": " )

    print("\n")

    if choice == "1":
        print(str(total_number) + " artifact(s) found.")

    elif choice == "2":
        for artifact_id, artifact in dict(artifacts).items():
            print("[" + str(artifact_id) + "] " + artifact["name"])
    
    elif choice == "3":
        target_to_delete = int(input(f"Which artifact to delete? (-1 to cancel) : " ))

        if(target_to_delete == -1):
            continue

        print("Currently " + str(total_number) + " artifact(s) found.\n")

        delete_artifact(target_to_delete)

        load_artifacts()
        print("Now " + str(total_number) + " artifact(s) found.\n")

    elif choice == "4":
        if(str(input(f"Are you sure you want to do this? (There is no undo)\nType YES to confirm: ")).upper() != "YES"):
            print("\nAborted.\n")
            continue
        else:
            print("Currently " + str(total_number) + " artifact(s) found.\n")
        
            for artifact_id, artifact in dict(artifacts).items():
                    delete_artifact(artifact_id)

            load_artifacts()
            print("Now " + str(total_number) + " artifact(s) found.\n")

    elif choice == "5":
        break
    else:
        print("Unknown selection.")

