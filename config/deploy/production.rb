role :app, %w{deploy@cityhapps.com}
server 'cityhapps.com', user: 'deploy', roles: %w{app}
set :application, 'cityhapps'
set :repo_url, proc { "git@github.com:E-Xor/cityhapps.git" }
set :deploy_to, proc { "/var/www/cityhapps" }
set :linked_files, %w{.env}
set :keep_releases, 2
set :linked_dirs, %w{public/uploads node_modules storage/logs storage/app storage/framework storage/cache vendor bootstrap/cache}
set :branch, "master"

set :ssh_options, {
  forward_agent: false,
}

namespace :deploy do
  desc 'Install dependencies with composer'
  after 'deploy:publishing', 'composer:install'

  desc 'Install dependencies with npm'
  task :npm do
    on roles(:app) do
      # npm config set production
      # npm config set jobs 1
      # Items above significantly improve speed as well ass --np-spin bellow
      execute :"source ~/.nvm/nvm.sh && cd #{fetch(:deploy_to)}/current && npm install --silent --no-progres --no-spin"
    end
  end
  after 'deploy:publishing', 'deploy:npm'

  desc 'Build assets'
  task :build do
    on roles(:app) do
      execute :"source ~/.nvm/nvm.sh && cd #{fetch(:deploy_to)}/current && npm run build"
    end
  end
  after 'deploy:publishing', 'deploy:build'
end
