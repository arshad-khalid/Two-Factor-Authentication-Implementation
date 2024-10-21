# Securing Web Applications through Two-Factor Authentication (2FA)

### Rationale

Introducing 2FA to enhance security for all users by significantly reducing the likelihood of unauthorized access. With 2FA, users would log in with a password and then provide a time-based OTP (One-Time Password) to gain access, ensuring system integrity.

**Benefits of 2FA**:

#### Tangible Benefits:
- Ensures identity verification by making it harder for intruders to gain access.
- Reduces costs by minimizing disruptions from unauthorized access.
- Meets regulatory requirements for secure login processes.

#### Intangible Benefits:
- Integrates smoothly with existing systems without requiring significant changes.
- Maintains system integrity by preventing unauthorized access.
- Increases security for remote users.
- Enhances user trust in the platform.

---

## Nature of Challenge

Adding 2FA to Moodle requires knowledge of PHP, as Most websites are developed in this language. A secure and reliable OTP generator must be integrated to ensure users can log in with enhanced protection. Moreover, safeguarding the system from common threats like SQL injection, a prevalent method for compromising databases, is a crucial aspect of the project.

---

## Project Objectives

### Deliverables

The implementation of 2FA will provide a more secure login experience for users via an authenticator OTP or email OTP. The project aims to significantly reduce unauthorized access and security breaches. Administrators will also have access to detailed login logs, enabling forensic analysis of suspicious login attempts.

### Core Objectives:
- Allow administrators to export login logs for further analysis.
- Integrate flexibility for using various authenticator apps for OTP generation.
- Enable OTPs to be sent via registered email.
- Generate unique, time-sensitive OTPs.
- Allow users to request a new OTP if needed.
- Reject login attempts if the OTP is incorrect.
- Authenticate the user if the OTP is correct.

---

## Resources Needed

### Hardware:
- Processor: Intel Core i5 / AMD Ryzen 5 (2.4GHz or higher)
- Disk Space: 5 GB
- RAM: 4 GB
- Mouse and Keyboard

### Software:
- [Visual Studio Code](https://code.visualstudio.com/) (2019)
- [MySQL](https://www.mysql.com/) or [MariaDB](https://mariadb.org/)
- [XAMPP](https://www.apachefriends.org/index.html) Web Server
- Web Browsers: Chrome, Firefox, Safari, Edge
- Microsoft Word (2016 or later)
- Authenticator App
- Registered email

---
