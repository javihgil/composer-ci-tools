# Global Configuration

The global default configuration is:

    {
        "extra": {
            "ci-tools": {
                "global": {
                    "error-format": "    <fg=red>%s</>",
                    "warning-format": "    <fg=yellow>%s</>",
                    "log-format": "    %s",
                    "report-results-path": "reports",
                    "test-results-path": "reports",
                }
            }
        }
    }

**error-format**

Format error lines. It must contains an "%s" expression to be replaced by text.

**warning-format**

Format warning lines. It must contains an "%s" expression to be replaced by text.

**log-format**

Format log lines. It must contains an "%s" expression to be replaced by text.

**report-results-path**

Path of report results. If does not exist it will be created on reporting tasks.

**test-results-path**

Path of test results. If does not exist it will be created on testing tasks.