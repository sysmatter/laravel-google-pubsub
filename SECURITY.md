# Security Policy

## Supported Versions

We release patches for security vulnerabilities in the following versions:

| Version | Supported          |
|---------|--------------------|
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

**Note:** We typically support the current major version and the previous major version. Once a new major version is
released, we'll provide security updates for the previous version for a limited time.

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability, please email **[shavonn@sysmatter.com]** with the following
information:

- Type of issue (e.g., buffer overflow, SQL injection, cross-site scripting, etc.)
- Full paths of source file(s) related to the manifestation of the issue
- The location of the affected source code (tag/branch/commit or direct URL)
- Any special configuration required to reproduce the issue
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue, including how an attacker might exploit it

### What to Expect

- **Acknowledgment**: We'll acknowledge receipt of your report within 48 hours
- **Initial Assessment**: We'll provide an initial assessment within 5 business days
- **Updates**: We'll keep you informed of our progress
- **Fix Timeline**: We'll work to fix the issue as quickly as possible (typically within 30 days for critical issues)
- **Credit**: We'll credit you in the security advisory (unless you prefer to remain anonymous)

## Security Update Process

When we receive a security report:

1. We confirm the vulnerability and determine its impact
2. We develop a fix and create patches for all supported versions
3. We prepare a security advisory
4. We release the patched versions
5. We publish the security advisory

## Public Disclosure

We follow coordinated disclosure:

- We'll work with you to understand and fix the issue
- We ask that you don't publicly disclose the issue until we've released a fix
- Once a fix is available, we'll publish a security advisory
- We'll credit you (if you wish) in the advisory

## Security Best Practices

When using this package:

- Always use the latest version
- Keep your Laravel and PHP versions up to date
- Follow Laravel's security best practices
- Monitor our security advisories
- Subscribe to our releases to stay informed

## Scope

This security policy applies to:

- The current major version of this package
- Security vulnerabilities in the package code itself
- Security issues in package dependencies that affect this package

**Out of Scope:**

- Vulnerabilities in Laravel core (report these to Laravel directly)
- Issues in your application code
- General PHP or server configuration issues

## Comments on this Policy

If you have suggestions on how this process could be improved, please submit a pull request or open an issue.

---

Thank you for helping keep Laravel Google PubSub and our users safe!