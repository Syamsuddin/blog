# Contributing to Laravel Blog CMS

Thank you for considering contributing to Laravel Blog CMS! This document outlines the process for contributing to this project.

## 🤝 How to Contribute

### Reporting Bugs

1. **Check existing issues** first to avoid duplicates
2. **Use the bug report template** when creating new issues
3. **Include detailed information**:
   - Laravel version
   - PHP version
   - Operating system
   - Steps to reproduce
   - Expected vs actual behavior
   - Screenshots (if applicable)

### Suggesting Features

1. **Check existing feature requests** first
2. **Use the feature request template**
3. **Provide detailed description**:
   - Problem the feature solves
   - Proposed solution
   - Alternative solutions considered
   - Additional context

### Code Contributions

#### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Git

#### Getting Started

1. **Fork the repository**
```bash
git clone https://github.com/your-username/laravel-blog-cms.git
cd laravel-blog-cms
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Set up environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Create your feature branch**
```bash
git checkout -b feature/amazing-feature
```

#### Development Guidelines

##### Code Standards

- Follow **PSR-12** coding standard
- Use **meaningful variable names**
- Write **clear comments** for complex logic
- Follow **Laravel conventions**

##### Testing

- Write tests for new features
- Ensure existing tests pass
- Aim for good test coverage

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

##### Database Changes

- Create migrations for schema changes
- Update seeders if needed
- Test migrations on fresh database

```bash
php artisan make:migration create_example_table
php artisan migrate:fresh --seed
```

##### Frontend Changes

- Follow Bootstrap 5 conventions
- Ensure mobile responsiveness
- Test across different browsers
- Optimize images and assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

#### Commit Guidelines

Use conventional commit messages:

```
type(scope): description

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**
```bash
git commit -m "feat(auth): add password reset functionality"
git commit -m "fix(blog): resolve pagination issue on mobile"
git commit -m "docs(readme): update installation instructions"
```

#### Pull Request Process

1. **Update documentation** if needed
2. **Add tests** for new functionality
3. **Ensure all tests pass**
4. **Update CHANGELOG.md** if applicable
5. **Create pull request** with:
   - Clear title and description
   - Link to related issues
   - Screenshots for UI changes
   - Testing instructions

### Code Review Process

1. **Automated checks** must pass
2. **At least one maintainer** review required
3. **Address feedback** promptly
4. **Squash commits** before merge (if requested)

## 🎯 Areas for Contribution

### High Priority
- [ ] Unit and feature tests
- [ ] Performance optimizations
- [ ] Security improvements
- [ ] Accessibility enhancements
- [ ] Mobile responsiveness fixes

### Medium Priority
- [ ] Additional rich text editor options
- [ ] Email templates
- [ ] Advanced search functionality
- [ ] SEO improvements
- [ ] API endpoints

### Low Priority
- [ ] Theme customization
- [ ] Plugin system
- [ ] Multi-language support
- [ ] Advanced analytics
- [ ] Social media integration

## 🐛 Debugging

### Common Issues

1. **Database connection errors**
   - Check `.env` database settings
   - Ensure MySQL is running
   - Verify database exists

2. **Permission errors**
   - Check `storage/` and `bootstrap/cache/` permissions
   - Run `chmod -R 755 storage bootstrap/cache`

3. **Asset compilation errors**
   - Clear node_modules: `rm -rf node_modules && npm install`
   - Clear build cache: `npm run build`

4. **Cache issues**
   - Clear all caches: `php artisan optimize:clear`
   - Rebuild caches: `php artisan optimize`

### Debug Tools

```bash
# Enable debug mode
APP_DEBUG=true

# Check logs
tail -f storage/logs/laravel.log

# Database queries
DB_LOG_QUERIES=true

# Clear caches
php artisan optimize:clear
```

## 📝 Documentation

### What to Document

- New features and their usage
- Configuration options
- API endpoints
- Breaking changes
- Installation/setup changes

### Documentation Format

- Use clear, concise language
- Include code examples
- Add screenshots for UI features
- Update relevant markdown files

## 🔒 Security

### Reporting Security Issues

**DO NOT** open public issues for security vulnerabilities.

Instead, email security issues to: `security@blogcms.local`

Include:
- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if known)

### Security Guidelines

- Never commit sensitive data
- Use Laravel's built-in security features
- Validate all user inputs
- Sanitize outputs
- Use HTTPS in production
- Keep dependencies updated

## 🎉 Recognition

Contributors will be:
- Listed in CONTRIBUTORS.md
- Mentioned in release notes
- Given credit in documentation

## 📞 Getting Help

- **Documentation**: Check README.md first
- **Issues**: Search existing issues
- **Discussions**: Use GitHub Discussions for questions
- **Code Review**: Tag maintainers in PRs

## 📋 Templates

### Bug Report Template

```markdown
**Bug Description**
A clear description of the bug.

**Steps to Reproduce**
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected Behavior**
What you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment:**
- Laravel Version: [e.g. 11.0]
- PHP Version: [e.g. 8.2]
- Browser: [e.g. Chrome 120]
- OS: [e.g. macOS 14]

**Additional Context**
Any other context about the problem.
```

### Feature Request Template

```markdown
**Is your feature request related to a problem?**
A clear description of the problem.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
Alternative solutions or features considered.

**Additional context**
Any other context, mockups, or screenshots.
```

## 📄 License

By contributing, you agree that your contributions will be licensed under the same MIT License that covers the project.

---

Thank you for contributing to Laravel Blog CMS! 🚀
