require 'capybara/dsl'
require 'rspec/matchers'

class AdminRegistrationPage
  include Capybara::DSL
  include RSpec::Matchers

  attr_accessor :admin_user_first_name
  attr_accessor :admin_user_last_name
  attr_accessor :email
  attr_accessor :business_name

  SITE = { :production => "url_here",
           :staging => "url_here"}

  LOGINSITE = { :staging => "url_here"}

  PAGETITLE = "title_here"

  PASSWORD = "password_here"

  def initialize(session)
    @session = session
    admin_user_first_name =  @admin_user_first_name
    admin_user_last_name = @admin_user_last_name
    email = @email
  end





  def open
    visit SITE[:staging]
    page.title.should == PAGETITLE
    page.find(:css, "a.btn.btn-primary[href*='profiles/new']").click
  end

  def usergen
    @user = UsernameGenerator.new()
    @user_id = @user.get
    return @user_id
  end




  def fillfields(regtable)

    regtable.hashes.each do |user_row|

      page.select(user_row['Title'], :from => 'user_title' )
      #page.find(:css, '#user_title').select user_row['Title']
      page.fill_in('user_first_name', :with => user_row['Firstname'])
      page.fill_in('user_last_name', :with => user_row['Surname'])
      #strEmail = Time.now.strftime("fridaytest+%d%m%Y%I%M%S@wearefriday.com")
      page.fill_in('user_email', :with => user_row['email'])
      @strEmail = user_row['email']
      page.fill_in('user_password',:with => user_row['password'])
      page.fill_in('user_confirm_password',:with => user_row['confirm_password'])
      page.fill_in('user_security_question_1',:with => user_row['SecQ1'])
      page.find(:css, 'input[name = security_answer_1]').set(user_row['answer1'])
      page.fill_in('user_security_question_2',:with => user_row['SecQ2'])
      page.find(:css, 'input[name = security_answer_2]').set(user_row['answer2'])
      page.fill_in('user_security_question_3',:with => user_row['SecQ3'])
      page.find(:css, 'input[name = security_answer_3]').set(user_row['answer3'])
      page.select( user_row['adminrole'] , :from => 'user_role' )
    end

  end

  def login
    fill_in "WSU Access ID", :with => @strEmail
    fill_in "password", :with => PASSWORD
    click_button('Sign in')
  end

  def submit_admin_reg
    click_button('Submit request')
  end

  def user_signup(signup)
    click_button(signup)
  end

  def verify_admin_reg_successful
    page.should have_content('Thank you')
    page.should have_content('Your request has been submitted for approval')
  end

  def loginExistingAdmin
    visit('http://competition-admin.c.hsbc.demo.wearefriday.com')
    #click_link('Users')
    if find_field('Email').visible?
      fill_in 'session_username', :with=>'pragnesh.patel@wearefriday.com'
      fill_in 'session_password', :with=>'Password1'
      click_button('Sign in')
    end
  end

  def manageApplications
    click_link('Manage applicants')
  end

  def Signout
    click_link_or_button('Sign out')
  end

end