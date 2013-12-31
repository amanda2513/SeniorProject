require 'ostruct'
require 'capybara'
require 'capybara/cucumber'
require 'capybara/rspec'
require 'selenium-webdriver'
require 'ostruct'
require 'net/http'
require 'uri'
require 'anticipate'

World Anticipate


def environments
  { 
    'DEV' => OpenStruct.new({
      hostname: 'google.com'
      }),
    'CI' => OpenStruct.new({
      hostname: 'google.com'
      })
  }
end

def environment_setting
  @environment_setting ||= ENV['ENVIRONMENT'] || 'DEV'
end

def server 
  @current_environment ||= environments.fetch environment_setting
end

def app_host
  @app_host ||= "http://#{server.hostname}:#{server.port}"
end

Capybara.run_server = false
begin
  Capybara.default_driver = ENV.fetch("SELENIUM_DRIVER").to_sym
rescue Exception => e
  raise(e.to_s)
end
Capybara.app_host = app_host

def capybara_profiles
  (YAML.load_file(File.join(File.dirname(__FILE__), '..', '..', 'config', 'capybara.yml'))).fetch environment_setting
end

capybara_profiles.each do |profile|
  using_selenium_grid = (profile.fetch(:browser) == :remote)
  profile_name = profile.delete :profile_name
  p profile_name
  if using_selenium_grid
    capabilities = profile.delete(:desired_capabilities) { {} }
    capabilities = Selenium::WebDriver::Remote::Capabilities.new(capabilities)
    profile.merge!({ desired_capabilities: capabilities })
  end
  Capybara.register_driver profile_name do |app|
    Capybara::Selenium::Driver.new(app, profile)
  end
end

config = YAML::load_file("#{File.dirname(__FILE__)}/../../config/config.yml")
@url = config['url']
$uname = config['user_name']
$pass = config['password']

#Before do
#  visit @url
#end