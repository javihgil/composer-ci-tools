# GitHooksInstaller

This Script Handler allows to configure GIT Hooks composer commands.

This is useful to integrate composer task/events with GIT Hooks.

Obtain more information about GIT Hooks in [Git SCM Page](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks)

All of these tasks behaviour is similar:

First, you must configure GIT Hook including the task call in your composer.json. This configures GIT Hook to launch
our new "hook event", expediently in post-intall-event.

After configure, GIT Hooks will launch a <hook-name>-hook named script in your composer.json. So, the
 <hook-name>-hook script must be defined.

**Configuration**

    {
        "scripts": {
            "post-install-cmd": [
                "Jhg\\ComposerCiTools\\GitHooksInstaller::preCommit"
            ]
        }
    }

**Usage**

    {
        "scripts": {
            "pre-commit-hook": []
        }
    }

## applyPatchMsg

This task configure the apply-patch-msg GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::applyPatchMsg" function in your configuration script.

The "apply-patch-msg-hook" composer script will be launched on "apply-patch-msg" GIT Hook.

## commitMsg

This task configure the commit-msg GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::commitMsg" function in your configuration script.

The "commit-msg-hook" composer script will be launched on "commit-msg" GIT Hook.

## postUpdate

This task configure the post-update GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::postUpdate" function in your configuration script.

The "post-update-hook" composer script will be launched on "post-update" GIT Hook.

## preApplyPatch

This task configure the pre-apply-patch GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::preApplyPatch" function in your configuration script.

The "pre-apply-patch-hook" composer script will be launched on "pre-apply-patch" GIT Hook.

## preCommit

This task configure the pre-commit GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::preCommit" function in your configuration script.

The "pre-commit-hook" composer script will be launched on "pre-commit" GIT Hook.

## prepareCommit

This task configure the prepare-commit GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::prepareCommit" function in your configuration script.

The "prepare-commit-hook" composer script will be launched on "prepare-commit" GIT Hook.

## prePush

This task configure the pre-push GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::prePush" function in your configuration script.

The "pre-push-hook" composer script will be launched on "pre-push" GIT Hook.

## preRebase

This task configure the pre-rebase GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::preRebase" function in your configuration script.

The "pre-rebase-hook" composer script will be launched on "pre-rebase" GIT Hook.

## update

This task configure the update GIT Hook composer event.

To configure this Hook call "Jhg\\ComposerCiTools\\GitHooksInstaller::update" function in your configuration script.

The "updateg-hook" composer script will be launched on "update" GIT Hook.
