# Introduction #

This document currently contains two main things:
  * Frog version numbering scheme
  * How to do a Frog release

# Frog version numbering scheme #

Similar to most Open Source projects, the Frog project uses a numbering scheme which conforms to the X.Y.Z system. Its exact syntax is:

```
Frog v<99>.<99>.<99> [<<RC|SP> 99>]
```

In filenames, spaces are replaced by underscores and the entire filename is lowercase. Possible examples of this syntax would be:

```
frog_v100.zip    - filename example; Frog stable version 1.0.0
Frog v0.9.4 RC 1 - normal reference; Frog version 0.9.4 Release Candidate 1
frog_v0.9.3_SP_1 - filename example; Frog version 0.9.3 Security Patch 1
```

What do all these numbers and letters mean? First, let us look at the `<99>.<99>.<99>` piece, which is divided into Major.Minor.Fix:

  * Major version; with a major version number change, almost anything goes. Generally, features will be added, removed, changed, renamed, etc... A Major version can offcourse also contain simple bugfixes.
  * Minor version; minor version changes should generally remain backwards compatible. Usually, features will only be added. They will only be changed if it doesn't impact on backwards compatibility.
  * Fix version; fix versions only contain bugfixes. Features should never be added or removed and only changed if it doesn't affect backwards compatibility.

As for the rest of the naming scheme, RC stands for Release Candidate and SP stands for Security Patch:

  * Release Candidate; when the Frog dev team feels a version is nearing completion, they might release an RC version. This is intended to give the end user a possibility to preview the final version and will allow end users to assist in testing for bugs.
  * Security Patch; when a security issue is reported to the Frog dev team, the team decides on the issue's urgency. (see DevSecurityGuidelines) If the problem is deemed a high risk issue, the team may decide to release an SP. End users should always install SP versions.

## About Security Patches ##

Security Patches are based on stable Frog versions only. This means that a specific security patch is only valid for the Frog stable version it was released for. Each security patch should:
  * contain only the affected files
  * contain a clear readme.txt describing the issue and how to install the patch
  * be present in SVN; the patched files should be committed to SVN and tagged
  * be based on a stable Frog version

Security Patches should never be applied to SVN HEAD, only to previously tagged stable versions. When creating a security patch, this means you:
  1. Switch your Frog working copy to the tagged stable version from SVN the patch is applicable to. (for example: tags/REL\_0\_9\_3)
  1. Patch the affected files
  1. Commit your working copy with a new tag, similar to: REL\_0\_9\_3\_SP\_1
  1. Write a readme.txt describing the issue and how to install the patch
  1. Create a .zip and .tar.gz package containing only the readme.txt file and patched files
  1. Upload the packages to the site
  1. Write a blog post
  1. Add an entry to the Security Patches page on the site
  1. Announce the patch on the forum

# Actions to take when releasing a Frog version #

  1. Make sure the changelog.txt file is up-to-date
  1. Make sure all components are checked into the SVN repository
  1. Create an SVN tag similar to the examples below:
    * REL\_0\_9\_4
    * REL\_0\_9\_4\_RC\_1
  1. Create a .zip and .tar.gz package
  1. Upload both packages to the Frog website
  1. Update the downloads page on the site
  1. Test to see if the link to the files are correct (i.e. downloadable)
  1. Write a blog post about the release
  1. Write an entry in the Frog site forum's "General Discussion" or "Announcements" section pointing to the blog post for more details.
  1. If releasing a stable version, don't forget to update the version number in the "version" page of the Frog site.