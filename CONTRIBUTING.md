# CONTRIBUTING

## Contributing code via Github

Swoft currently uses Git to control the version of the program. If you want to contribute source code to Swoft, please get an overview of how Git is used. We currently host the project on GitHub, and any GitHub user can contribute code to us.

The way to participate is very simple, fork a swoft-component or swoft-ext code into your warehouse, modify and submit, and send us a pull request, we will promptly review the code and process your application. After the review, your code will be merged into our repository, so you will automatically appear on the contributor list, very convenient.

We hope that the code you contributed will be:

- Swoft's coding specification
- Appropriate comments that others can read
- Follow the Apache2 open source protocol
- Submit a commit message must be in English

> If you would like more details or have any questions, please continue reading below

Precautions

- PSR-2 is selected for the code formatting standard of this project;
- class name and class file name follow PSR-4;
- For the processing of Issues, use the commit title such as `fix swoft-cloud/swoft#xxx(Issue ID)` to directly close the issue.
- The system will automatically test and modify on PHP 7.1+ (`7.1 7.2 7.3`) Swoole 4.4.1+
- The administrator will not merge the changes that caused CI faild. If CI faild appears, please check your source code or modify the corresponding unit test file.

## GitHub Issue

GitHub provides the Issue feature, which can be used to:

- Raise a bug
- propose functional improvements
- Feedback experience

This feature should not be used for:

- Unfriendly remarks

## Quick modification

GitHub provides the ability to quickly edit files

- Log in to your GitHub account;
- Browse the project file and find the file to be modified;
- Click the pencil icon in the upper right corner to modify it;
- Fill in Commit changes related content (Title is required)
- Submit changes, wait for CI verification and administrator merge.

> This method is suitable for modifying the document project. If you need to submit a large number of changes at once, please continue reading the following

## Complete process

- fork swoft-component or swoft-ext project;
- Clone your fork project to the local;
- Create a new branch and checkout the new branch;
- Make changes. If your changes include additions or subtractions of methods or functions, please remember to modify the unit test file.
- Push your local repository to GitHub;
- submit a pull request;
- Wait for CI verification (if you don't pass, you need to fix the code yourself, GitHub will automatically update your pull request);
- Waiting for administrator processing

## Precautions

If you have any questions about the above process, please check out the GIT tutorial;

For different aspects of the code, create different branches in your own fork project.
