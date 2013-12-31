class UserDetails
  attr_reader :generated

  def initialize()
    #@len = len
    @generated = []
  end

  def get_email
    @user = UsernameGenerator.new()
    @user_id = @user.get_user_id
    @email = @user_id + "@" +"dispostable.com"
  end

  def get_first_name
    @user = UsernameGenerator.new()
    @last_name = @user.get_first_name
  end

  def get_last_name
    @user = UsernameGenerator.new()
    @first_name = @user.get_last_name

  end

  def user_details(first_name,last_name, department, title, description)
    visit('https://calypso.gc.hsbc.staging.wearefriday.com:444/competitions/hsbc-registration/registrations')
    find_link('Create a NEW HSBC Global Connections profile').click
    page.select("Mrs", :from=> 'Title')
    fill_in 'First name', :with =>  user_first_name
    @admin_user_first_name = find_field('First name').value
    fill_in 'Surname', :with =>  user_last_name
    @admin_user_last_name =  find_field('Surname').value
    fill_in 'user_email', :with=> email
    @email = find_field('user_email').value
    fill_in 'user_password', :with=> password
    fill_in 'user_confirm_password', :with=> password
  end

  def get_category_name
    random_string_first_name = (0...5).map{ ('A'..'Z').to_a[rand(26)]  }.join
    (0...8).map{ ('A'..'Z').to_a[rand(26)]  }.join
    @category_name =  random_string_first_name
    #p @user_id
    return @category_name
  end

  def get_subcategory_name
    random_string_first_name = (0...5).map{ ('A'..'Z').to_a[rand(26)]  }.join
    (0...8).map{ ('A'..'Z').to_a[rand(26)]  }.join
    @subcategory_name =  random_string_first_name
    #p @user_id
    return @subcategory_name
  end

  def get_subcategory_desc
    random_string_first_name = (0...15).map{ ('A'..'Z').to_a[rand(26)]  }.join
    (0...8).map{ ('a'..'z').to_a[rand(26)]  }.join
    @subcategory_desc =  random_string_first_name
    #p @user_id
    return @subcategory_desc
  end



end