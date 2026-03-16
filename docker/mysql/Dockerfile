# ──────────────────────────────────────────
# Dipoddi API — MySQL 8.0 for Sliplane
# ──────────────────────────────────────────
# Deploy as a separate Sliplane service on the same server.
# Internal hostname: dipodi-mysql.internal
#
# Required env vars (set in Sliplane dashboard):
#   MYSQL_ROOT_PASSWORD  — strong random password (40+ chars)
#   MYSQL_DATABASE       — dipodi_api
#   MYSQL_USER           — dipodi
#   MYSQL_PASSWORD       — strong random password (40+ chars)
# ──────────────────────────────────────────

FROM mysql:8.0

# Copy custom MySQL configuration
COPY my.cnf /etc/mysql/conf.d/dipoddi.cnf

# Copy initialization script (runs once on first startup)
COPY init.sql /docker-entrypoint-initdb.d/01-init.sql

# MySQL default port
EXPOSE 3306

# Data is stored in /var/lib/mysql — attach a Sliplane volume here
VOLUME ["/var/lib/mysql"]
