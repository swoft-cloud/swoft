# Swoft Contributing Guide

Hi! I am really excited that you are interested in contributing to Swoft. Before submitting your contribution though, please make sure to take a moment and read through the following guidelines.

- [Code of Conduct](./.github/CODE_OF_CONDUCT.md)
- [Issue Reporting Guidelines](#issue-reporting-guidelines)
- [Pull Request Guidelines](#pull-request-guidelines)
- [Development Guidelines](#development-guidelines)

## Issue Reporting Guidelines

- Should always create an new issues by Github issue template, to avoid missing information.

## Pull Request Guidelines

The master branch is the latest stable release, the feature branch commonly is the next feature upgrade version. If it's a feature develoment, should be done in feature branch, if it's a bug-fix develoment, then you could done in master branch, or feature branch, the different between master branch an feature branch is that the feature branch will merge to master branch until next version upgraded, and master branch could release a bug-fix version anytime.

Note that Swoft using [swoft-component](https://github.com/swoft-cloud/swoft-component) repository to centralized manage all Swoft components, if you want to submit an PR for component of swoft, then you should submit your PR to [swoft-component](https://github.com/swoft-cloud/swoft-component) repository.

Checkout a topic branch from the relevant branch, e.g. feature, and merge back against that branch.

It's OK to have multiple small commits as you work on the PR - we will let GitHub automatically squash it before merging.

Make sure unit test passes, commonly you could use `composer test` to run all unit testes. (see development setup)

If adding new feature:

Add accompanying test case.
Provide convincing reason to add this feature. Ideally you should open a suggestion issue first and have it greenlighted before working on it.
If fixing a bug:

If you are resolving a special issue, add (fix #xxxx[,#xxx]) (#xxxx is the issue id) in your PR title for a better release log, e.g. update entities encoding/decoding (fix #3899).
Provide detailed description of the bug in the PR. Live demo preferred.
Add appropriate test coverage if applicable.

## Development Guidelines

Because Swoft using [swoft-component](https://github.com/swoft-cloud/swoft-component) repository to centralized manage all Swoft components, then you should add `swoft/component` requires to `composer.json` if you are developing in swoft forked repository, after this, components of swoft-component will replace all original components requires, see [Composer replace schema](https://getcomposer.org/doc/04-schema.md#replace) for more details.

composer requires e.g.

```json
"require": {
    "swoft/component": "dev-master as 1.0"
},
```
