# Enter script code
import os
import sys
import json
import time
import random
from shutil import copyfile
from shutil import rmtree
import errno

def empty_dir(dir_to_empty):
    for root, dirs, files in os.walk(dir_to_empty):
        for f in files:
            os.unlink(os.path.join(root, f))
        for d in dirs:
            shutil.rmtree(os.path.join(root, d))
 
def copytree(src, dst, symlinks=False, ignore=None):
    for item in os.listdir(src):
        s = os.path.join(src, item)
        d = os.path.join(dst, item)
        if os.path.isdir(s):
            shutil.copytree(s, d, symlinks, ignore)
        else:
            shutil.copy2(s, d)

def typing_in_character(content):
    for c in content:
        time.sleep(random.randint(1,8)*0.01)
        keyboard.send_keys(c)

auto_table = "auto.table"
base = os.path.basename(auto_table)
filename_noext = os.path.splitext(base)[0]

def typing_script(filename):
    f = open(filename)
    for line in f.readlines():
        # line = unicode(line, 'utf-8')
        if line[:4] == "cmd:":
            keyboard.send_keys(line[4:].strip("\n\r"))
        elif line[:5] == "wait:":
            time.sleep(int(line[5:]))
        elif line[:6] == "mouse:":
            mouse.wait_for_click(int(line[6:]), 100)
        elif line[:10] == "voko:start":
            time.sleep(2)
            window.activate("vokoscreen")
            time.sleep(2)
            keyboard.send_keys("<ctrl>+<shift>+<f10>")
        elif line[:9] == "voko:stop":
            time.sleep(2)
            window.activate("vokoscreen")
            time.sleep(2)
            keyboard.send_keys("<ctrl>+<shift>+<f11>")
        elif line[:7] == "switch:":
            window.activate(line[7:].strip())
            # window.wait_for_focus(line[7:])
            time.sleep(2)
        elif line[:9] == "nano:save":
            time.sleep(2)
            keyboard.send_keys("<ctrl>+xy<enter>")
            time.sleep(2)
        elif line[:8] == "nano:end":
            keyboard.send_keys("<escape>/")
        elif line[:9] == "nano:exit":
            time.sleep(2)
            keyboard.send_keys("<ctrl>+x")
            time.sleep(2)
        elif line[:9] == "nano:mark":
            keyboard.send_keys("<ctrl>+<shift>+6")
        elif line[:12] == "nano:zoomout":
            keyboard.send_keys("<ctrl>+_")
        elif line[:11] == "nano:zoomin":
            keyboard.send_keys("<ctrl>+<shift>+=")
        elif line[:11] == "nano:indent":
            indentTimes = int(line[12:14])
            indent = "}" if indentTimes > 0 else "{"
            indent = "<alt>+<shift>+"+indent
            keyboard.send_keys(indent * indentTimes)
        elif line[:11] == "nano:search":
            keyboard.send_keys("<ctrl>+w")
            moveTimes = int(line[12:14])
            move = "<down>" if moveTimes > 0 else "<up>"
            keyboard.send_keys(line[14:].strip() + "\n")
            if moveTimes:
                keyboard.send_keys(move * abs(moveTimes))
        elif line.rstrip(" \n\t\r"):
            tmp = line.rstrip()+"\n"
            # tmp = tmp.replace("    ", "\t")
            typing_in_character(tmp)
            if len(tmp) > 3:
                time.sleep(0.5)
        else:
            typing_in_character("\n")

    f.close()




auto_table = "auto.table"
root_dir_symfans = os.getcwd() + "/symfans"
root_dir_sources = root_dir_symfans + "/symfans-sources"
root_dir_projects = root_dir_symfans + "/symfans-projects"
root_dir_courses = root_dir_symfans + "/symfans-courses"

for path in os.listdir(root_dir_sources):
    #loop finding auto.table in the sources dir's subdir
    script_root = root_dir_sources + "/" + path + "/_scripts"
    backup_root = root_dir_sources + "/" + path + "/_backup"

    cfg_file = script_root + "/" + auto_table
    if os.path.isfile(cfg_file) and os.path.exists(cfg_file):
        #load cfg from auto.table
        cfg = json.load(open(cfg_file, "r"))
        if cfg["enabled"]:
            #start auto-scripting one source    
            first = True
            prev_backup_root = ""
            prev_script_enabled = False

            for script, enabled in sorted(cfg["scripts"].items()):
                dist_project_root = root_dir_projects + "/" + path

                if first and enabled and os.path.exists(dist_project_root):
                    shutil.rmtree(dist_project_root)

                backup_source_root = backup_root + "/" + os.path.splitext(script)[0]
                if enabled:
                    if not prev_script_enabled and prev_backup_root:
                        if os.path.isdir(dist_project_root):
                            empty_dir(dist_project_root)
                        else:
                            os.makedirs(dist_project_root)

                        if os.path.isdir(prev_backup_root):
                            copytree(prev_backup_root, dist_project_root)


                    typing_script(script_root + "/" + script)

                    if os.path.exists(backup_source_root) and os.path.exists(dist_project_root):
                        empty_dir(backup_source_root)

                    if not os.path.exists(backup_source_root):
                        os.makedirs(backup_source_root)
                       
                    if os.path.exists(dist_project_root):
                        copytree(dist_project_root, backup_source_root)

                first = False
                prev_backup_root = backup_source_root
                prev_script_enabled = enabled

                        
        


sys.exit(0)

f = open(os.getcwd() + "/symfans-courses/scripts/" + auto_table, "r")
data = json.load(f)


for subject in data["subjects"]:
    root = subject["root"]
    path = os.getcwd() + "/" + root + "/" + subject["src"] + "/"
    dist = os.getcwd() + "/" + root + "/" + subject["dist"]
    backup = os.getcwd() + "/" + root + "/" + subject["backup"] + "/"
    if subject["enabled"] :     
        # window.activate("kendoctor@kendoctor-VirtualBox")
        # if window.wait_for_focus("kendoctor@kendoctor-VirtualBox"):  
        if 1:  
            typing_in_character("cd ~\n")
            typing_in_character("cd " + root  + "\n")
            keyboard.send_keys("rm -rf " + dist + "\n")
            keyboard.send_keys("clear\n")
            time.sleep(2)
            typing_in_character("mkdir " + subject["dist"]  + "\n")
            typing_in_character("cd " + subject["dist"]  + "\n")

            index = 0
            prev_enabled = False
            for script, enabled in sorted(subject["scripts"].items()):
                if not prev_enabled and os.path.exists(backup + subject["dist"] + "/s" + str(index)):
                    rmtree(dist)
                    copytree(backup + subject["dist"] + "/s" + str(index), dist)
                    time.sleep(1)
                    keyboard.send_keys("cd " + dist + "\nclear\n")

                index +=1
                prev_enabled = enabled
                if enabled:        
                    typing_script(path + script)
                    if os.path.exists(backup + subject["dist"] + "/s" + str(index)):
                        rmtree(backup + subject["dist"] + "/s" + str(index))
                    copytree(dist, backup + subject["dist"] + "/s" + str(index))



sys.exit(0)

for line in f.readlines():
    filename = line.strip("\n\r")
    if filename and filename[0] != "#":
        typing_script(os.getcwd() + "/symfans/scripts/" + filename)


