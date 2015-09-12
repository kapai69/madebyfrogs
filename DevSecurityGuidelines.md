# Introduction #

This document describes all security related guidelines for the Frog development team.

# Security Issues - What to do? #

This describes what dev team members should do if/when a security related issue is found.

### When fixing it yourself ###
  * If the problem hasn't been reported in the issue list, create an issue for it.
    * mark the issue as "Critical"
    * also mark the issue as "Security" related
    * give a clear summary
    * describe the steps to reproduce the issue and clearly state the **expected** and **actual** outcomes.
  * Implement a fix
  * Create a post in the Security discussion within the FrogDev discussion group. (see DevTemplates)
    * give the title
    * give a link to the issue
    * give a description
    * mention the fix and link to the fix's revision in SVN

### When you can't fix it ###
  * If the problem hasn't been reported in the issue list, create an issue for it.
    * mark the issue as "Critical"
    * also mark the issue as "Security" related
    * give a clear summary
    * describe the steps to reproduce the issue and clearly state the **expected** and **actual** outcomes.
  * Create a post in the Security discussion within the FrogDev discussion group. (see DevTemplates)
    * give the title
    * give a link to the issue
    * give a description
    * mention the fix and link to the fix's revision in SVN
  * **Clearly** mark the issue as **NOT FIXED** so another dev team member may pick it up.

### After fix is implemented ###

At least one other Frog dev team member should test the patch and stability of Frog.

Depending on the severity of the issue, either:
  * an immediate security patch is released against the current version of Frog
  * the current version of Frog available for download is upgraded to include at least the security related fix.

OR

  * the current version of Frog available for download is upgraded to include at least the security related fix
  * no seperate security patch is released.